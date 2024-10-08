<?php

namespace App\Filament\Admin\Resources\OrdenResource\Pages;

use App\Enums\Examen\TipoExamenEnum;
use App\Enums\Unidad\OperadoresEnum;
use App\Enums\Unidad\TipoUnidadEnum;
use App\Filament\Admin\Resources\OrdenResource;
use App\Models\Examen;
use App\Models\ExamenOrden;
use App\Models\Orden;
use Filament\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RegistrarResultados extends EditRecord
{
    use EvaluatesClosures;
    protected static string $resource = OrdenResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // dd($data);
        // $data['ordenExamens'][0] = ['nombre' => 's'];

        return $data;
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // dd($data);
        $record->update($data);

        return $record;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('ordenExamens')
                    ->hiddenLabel()
                    ->relationship(modifyQueryUsing: fn(Builder $query) => $query->whereHas('examen', fn($query) => $query->where('parent_id', null)))
                    ->mutateRelationshipDataBeforeFillUsing(function (array $data, Orden $record): array {
                        $examen = $record->examens->find($data['examen_id']);
                        $data['nombre'] = $examen->nombre;
                        return $data;
                    })
                    ->addable(false)
                    ->deletable(false)
                    ->itemLabel(fn(array $state): ?string => $state['nombre'] ?? null)
                    ->schema([
                        // Group::make()
                        Hidden::make('disabled')->default(true)->dehydrated(false)->live(),
                        Hidden::make('is_canceled')->live(),
                        Group::make(fn(ExamenOrden $record) => [
                            DatePicker::make('fecha_resultado')
                                ->default(now())
                                ->required(),
                            TextInput::make('motivo')
                                ->required()
                                ->visible(fn(Get $get) => $get('is_canceled')),
                            Group::make([
                                ...(match ($record->examen->tipo) {
                                    TipoExamenEnum::STRING->value => [TextInput::make('resultado')],
                                    TipoExamenEnum::RESPUESTA->value =>
                                    [
                                        Select::make('respuesta_id')
                                            ->label('Respuesta')
                                            ->options(fn(ExamenOrden $record) => $record->examen->respuestas->pluck('nombre', 'id'))
                                    ]
                                    ,
                                    TipoExamenEnum::UNIDAD->value => [
                                        TextInput::make('resultado')
                                            ->numeric()
                                            ->required()
                                            ->live()
                                            ->extraInputAttributes(function (ExamenOrden $record, Get $get, $state) {
                                                    $positivo = false;
                                                    $floatState = (float) $state;
                                                    // dd($floatState);
                                                    if ($get('unidad_id')) {
                                                        $unidad = $record->examen->unidads->find($get('unidad_id'));
                                                        switch ($unidad->pivot->tipo) {
                                                            case TipoUnidadEnum::UNICO->value:
                                                                $positivo = $floatState === $unidad->pivot->minimo;
                                                                break;

                                                            case TipoUnidadEnum::MULTIVALOR->value:
                                                                // Verifica si $floatState está entre $unidad->pivot->minimo y $unidad->pivot->maximo
                                                                $positivo = $floatState >= $unidad->pivot->minimo && $floatState <= $unidad->pivot->maximo;
                                                                break;

                                                            case TipoUnidadEnum::OPERADOR->value:
                                                                switch ($unidad->pivot->operador) {
                                                                    case OperadoresEnum::MENOR->value:
                                                                        // Verificar si $floatState es menor que $unidad->pivot->minimo
                                                                        $positivo = $floatState < $unidad->pivot->minimo;
                                                                        break;
                                                                    case OperadoresEnum::MENOR_IGUAL->value:
                                                                        // Verificar si $floatState es menor o igual que $unidad->pivot->minimo
                                                                        $positivo = $floatState <= $unidad->pivot->minimo;
                                                                        break;
                                                                    case OperadoresEnum::MAYOR->value:
                                                                        // Verificar si $floatState es mayor que $unidad->pivot->minimo
                                                                        $positivo = $floatState > $unidad->pivot->minimo;
                                                                        break;
                                                                    case OperadoresEnum::MAYOR_IGUAL->value:
                                                                        // Verificar si $floatState es mayor o igual que $unidad->pivot->minimo
                                                                        $positivo = $floatState >= $unidad->pivot->minimo;
                                                                        break;
                                                                    default:
                                                                        // Si no coincide con ningún operador válido, el resultado no es positivo
                                                                        $positivo = false;
                                                                }
                                                                break;
                                                        }
                                                    }
                                                    return ['class' => $positivo ? 'bg-green-100' : 'bg-red-100'];
                                                })
                                            ->helperText(function (ExamenOrden $record, Get $get) {
                                                    if ($get('unidad_id')) {
                                                        $unidad = $record->examen->unidads->find($get('unidad_id'));
                                                        return match ($unidad->pivot->tipo) {
                                                            TipoUnidadEnum::UNICO->value => "= a $unidad->pivot->minimo",
                                                            TipoUnidadEnum::MULTIVALOR->value => "Entre {$unidad->pivot->minimo} a {$unidad->pivot->maximo}",
                                                            TipoUnidadEnum::OPERADOR->value => "Es {$unidad->pivot->operador} {$unidad->pivot->minimo}",
                                                            default => null,
                                                        };
                                                    }
                                                }),
                                        Select::make('unidad_id')
                                            ->label('Unidad')
                                            ->options(fn(ExamenOrden $record) => $record->examen->unidads->pluck('nombre', 'id'))
                                            ->required()
                                            ->live()
                                    ],
                                    TipoExamenEnum::PADRE->value => [
                                        Repeater::make('examenChildren')
                                            ->hiddenLabel()
                                            ->columnSpan(3)
                                            ->relationship()
                                            ->addable(false)
                                            ->deletable(false)
                                            ->itemLabel(fn(array $state): ?string => $state['nombre'] ?? null)
                                            ->saveRelationshipsUsing(function ($state, ExamenOrden $record) {
                                                    $examensData = [];
                                                    foreach ($state as $value) {
                                                        $examensData[$value['id']] = [
                                                        'unidad_id' => $value['unidad_id'] ?? null,
                                                        'respuesta_id' => $value['respuesta_id'] ?? null,
                                                        'resultado' => $value['resultado'] ?? null,
                                                        ];
                                                    }
                                                    $record->orden->examens()->syncWithoutDetaching($examensData);
                                                    return [];
                                                })
                                            ->schema([
                                                Group::make(fn(Examen $record) => match ($record->tipo) {
                                                        TipoExamenEnum::STRING->value => [TextInput::make('resultado')],
                                                        TipoExamenEnum::RESPUESTA->value => [
                                                            Select::make('respuesta_id')
                                                                ->label('Respuesta')
                                                                ->options(fn(Examen $record) => $record->respuestas->pluck('nombre', 'id'))
                                                        ],
                                                        TipoExamenEnum::UNIDAD->value => [
                                                            TextInput::make('resultado')
                                                                ->numeric()
                                                                ->required()
                                                                ->live()
                                                                ->extraInputAttributes(function (Examen $record, Get $get, $state) {
                                                                            $positivo = false;
                                                                            $floatState = (float) $state;

                                                                            if ($get('unidad_id')) {
                                                                                $unidad = $record->unidads->find($get('unidad_id'));
                                                                                switch ($unidad->pivot->tipo) {
                                                                                    case TipoUnidadEnum::UNICO->value:
                                                                                        $positivo = $floatState === $unidad->pivot->minimo;
                                                                                        break;

                                                                                    case TipoUnidadEnum::MULTIVALOR->value:
                                                                                        // Verifica si $floatState está entre $unidad->pivot->minimo y $unidad->pivot->maximo
                                                                                        $positivo = $floatState >= $unidad->pivot->minimo && $floatState <= $unidad->pivot->maximo;
                                                                                        break;

                                                                                    case TipoUnidadEnum::OPERADOR->value:
                                                                                        switch ($unidad->pivot->operador) {
                                                                                            case OperadoresEnum::MENOR->value:
                                                                                                // Verificar si $floatState es menor que $unidad->pivot->minimo
                                                                                                $positivo = $floatState < $unidad->pivot->minimo;
                                                                                                break;
                                                                                            case OperadoresEnum::MENOR_IGUAL->value:
                                                                                                // Verificar si $floatState es menor o igual que $unidad->pivot->minimo
                                                                                                $positivo = $floatState <= $unidad->pivot->minimo;
                                                                                                break;
                                                                                            case OperadoresEnum::MAYOR->value:
                                                                                                // Verificar si $floatState es mayor que $unidad->pivot->minimo
                                                                                                $positivo = $floatState > $unidad->pivot->minimo;
                                                                                                break;
                                                                                            case OperadoresEnum::MAYOR_IGUAL->value:
                                                                                                // Verificar si $floatState es mayor o igual que $unidad->pivot->minimo
                                                                                                $positivo = $floatState >= $unidad->pivot->minimo;
                                                                                                break;
                                                                                            default:
                                                                                                // Si no coincide con ningún operador válido, el resultado no es positivo
                                                                                                $positivo = false;
                                                                                        }
                                                                                        break;
                                                                                }
                                                                            }
                                                                            return ['class' => $positivo ? 'bg-green-200' : 'bg-red-200'];
                                                                        })
                                                                ->helperText(function (Examen $record, Get $get) {
                                                                            if ($get('unidad_id')) {
                                                                                $unidad = $record->unidads->find($get('unidad_id'));
                                                                                return match ($unidad->pivot->tipo) {
                                                                                    TipoUnidadEnum::UNICO->value => "= a $unidad->pivot->minimo",
                                                                                    TipoUnidadEnum::MULTIVALOR->value => "Entre {$unidad->pivot->minimo} a {$unidad->pivot->maximo}",
                                                                                    TipoUnidadEnum::OPERADOR->value => "Es {$unidad->pivot->operador} {$unidad->pivot->minimo}",
                                                                                    default => null,
                                                                                };
                                                                            }
                                                                        }),
                                                            Select::make('unidad_id')
                                                                ->label('Unidad')
                                                                ->options(fn(Examen $record) => $record->unidads->pluck('nombre', 'id'))
                                                                ->required()
                                                                ->live(),
                                                        ],
                                                        default => [TextInput::make('hola')],
                                                    })->columnSpanFull()->columns(2),
                                            ])
                                            ->mutateRelationshipDataBeforeFillUsing(function (array $data, ExamenOrden $record): array{
                                                    $children = $record->orden->examens->find($data['id']);
                                                    $data['unidad_id'] = $children->pivot->unidad_id;
                                                    $data['nombre'] = $children->nombre;
                                                    $data['respuesta_id'] = $children->pivot->respuesta_id;
                                                    $data['resultado'] = $children->pivot->resultado;
                                                    return $data;
                                                })
                                    ],
                                    default => [TextInput::make('hola')],
                                })
                            ])
                                ->columnSpan(3)->columns(2)
                                ->visible(fn(Get $get) => !$get('is_canceled'))

                        ])
                            ->visible(fn(Get $get) => !$get('disabled'))
                            ->columnSpan(3)->columns(2),
                    ])
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        if ((bool) $data['is_canceled']) {
                            $data['unidad_id'] = null;
                            $data['resultado'] = null;
                            $data['respuesta_id'] = null;
                        } else {
                            $data['motivo'] = null;
                        }
                        return $data;
                    })
                    ->extraItemActions([
                        Action::make('deshabilitar')
                            ->icon(function (array $arguments, RegistrarResultados $livewire) {
                                $statePath = $livewire->getFormStatePath();
                                $x = data_get($livewire, $statePath . '.ordenExamens.' . $arguments['item'] . '.disabled');
                                return $x ? 'fluentui-checkmark-circle-20-o' : 'fluentui-presence-blocked-10-o';
                            })
                            ->color(function (array $arguments, RegistrarResultados $livewire) {
                                $statePath = $livewire->getFormStatePath();
                                $x = data_get($livewire, $statePath . '.ordenExamens.' . $arguments['item'] . '.disabled');
                                return $x ? 'primary' : 'danger';
                            })
                            ->tooltip(function (array $arguments, RegistrarResultados $livewire) {
                                $statePath = $livewire->getFormStatePath();
                                $x = data_get($livewire, $statePath . '.ordenExamens.' . $arguments['item'] . '.disabled');
                                return $x ? 'Habilitar' : 'Deshabilitar';
                            })
                            ->action(function (array $arguments, RegistrarResultados $livewire) {
                                $statePath = $livewire->getFormStatePath();
                                $x = data_get($livewire, $statePath . '.ordenExamens.' . $arguments['item'] . '.disabled');
                                data_set($livewire, $statePath . '.ordenExamens.' . $arguments['item'] . '.disabled', !$x);
                            }),
                        Action::make('cancelarExamen')
                            ->icon(function (array $arguments, RegistrarResultados $livewire) {
                                $statePath = $livewire->getFormStatePath();
                                $x = data_get($livewire, $statePath . '.ordenExamens.' . $arguments['item'] . '.is_canceled');
                                return $x ? 'tabler-pencil-plus' : 'tabler-circle-x';
                            })
                            ->color(function (array $arguments, RegistrarResultados $livewire) {
                                $statePath = $livewire->getFormStatePath();
                                $x = data_get($livewire, $statePath . '.ordenExamens.' . $arguments['item'] . '.is_canceled');
                                return $x ? 'warning' : 'danger';
                            })
                            ->tooltip(function (array $arguments, RegistrarResultados $livewire) {
                                $statePath = $livewire->getFormStatePath();
                                $x = data_get($livewire, $statePath . '.ordenExamens.' . $arguments['item'] . '.is_canceled');
                                return $x ? 'Editar' : 'Cancelar';
                            })
                            ->action(function (array $arguments, RegistrarResultados $livewire) {
                                $statePath = $livewire->getFormStatePath();
                                $x = data_get($livewire, $statePath . '.ordenExamens.' . $arguments['item'] . '.is_canceled');
                                data_set($livewire, $statePath . '.ordenExamens.' . $arguments['item'] . '.is_canceled', !$x);
                            }),
                    ])
                    ->columns(3)
            ])->columns(null);
    }
}
