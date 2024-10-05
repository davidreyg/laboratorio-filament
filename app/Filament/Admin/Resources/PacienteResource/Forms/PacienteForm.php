<?php

namespace App\Filament\Admin\Resources\PacienteResource\Forms;
use App\Actions\BuscarReniec;
use App\Models\TipoDocumento;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component as Livewire;
class PacienteForm
{
    public static function form(): array
    {
        return [
            Select::make('tipo_documento_id')
                ->label('Tipo de Documento')
                ->required()
                ->options(TipoDocumento::pluck('nombre', 'id'))
                ->live(),
            TextInput::make('numero_documento')
                ->required()
                ->numeric()
                ->unique(table: 'pacientes', column: 'numero_documento', ignoreRecord: true)
                ->suffixAction(
                    fn(Get $get) => $get('tipo_documento_id') == 1
                    ? Action::make('buscarPorDni')
                        ->label('Buscar')
                        ->icon('tabler-search')
                        ->action(function (Livewire $livewire, TextInput $component, $state, Set $set) {
                            $livewire->validateOnly($component->getKey());
                            $data = BuscarReniec::make()->handle($state);
                            // dd($data);
                            $setValues = [
                                'nombres' => 'nombres',
                                'apellido_paterno' => 'apellido_paterno',
                                'apellido_materno' => 'apellido_materno',
                                'fecha_nacimiento' => 'fecha_nacimiento',
                                // 'edad' => 'edad',
                                'sexo' => 'sexo',
                                'direccion' => 'direccion',
                            ];
                            foreach ($setValues as $key => $value) {
                                if ($key === 'sexo') {
                                    $set($key, $data[$value] == 1 ? 'Masculino' : 'Femenino');
                                } else if ($key === 'fecha_nacimiento') {
                                    // Crear un objeto Carbon desde la fecha proporcionada en formato "dd/mm/yyyy"
                                    $fechaCarbon = Carbon::createFromFormat('d/m/Y', $data[$value]);
                                    $edad = $fechaCarbon->age;

                                    // Asigna la edad calculada al campo "edad"
                                    $set($key, $fechaCarbon->toDateString() ?? null);
                                    $set('edad', $edad);

                                } else {
                                    $set($key, $data[$value] ?? null);
                                }
                            }
                        })
                    : null
                ),
            TextInput::make('nombres')
                ->required()
                ->maxLength(50),
            TextInput::make('apellido_paterno')
                ->required()
                ->maxLength(50),
            TextInput::make('apellido_materno')
                ->required()
                ->maxLength(50),
            DatePicker::make('fecha_nacimiento')
                ->required()
                ->afterStateUpdated(function (callable $set, $state) {
                    // Verifica si se ha ingresado una fecha válida
                    if ($state) {
                        // Calcula la edad con Carbon
                        $edad = Carbon::parse($state)->age;

                        // Asigna la edad calculada al campo "edad"
                        $set('edad', $edad);
                    }
                })
                ->live(),
            TextInput::make('edad')
                ->required()
                ->numeric(),
            TextInput::make('sexo')
                ->required()
                ->maxLength(10),
            TextInput::make('direccion')
                ->required()
                ->maxLength(200),
            TextInput::make('telefono')
                ->tel()
                ->numeric()
                ->default(null),
            TextInput::make('historia_clinica')
                ->required()
                ->maxLength(50),

        ];
    }

}