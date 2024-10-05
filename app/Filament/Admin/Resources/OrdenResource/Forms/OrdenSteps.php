<?php

namespace App\Filament\Admin\Resources\OrdenResource\Forms;
use App\Filament\Admin\Resources\PacienteResource\Forms\PacienteForm;
use App\Models\Paciente;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Novadaemon\FilamentCombobox\Combobox;
class OrdenSteps
{
    public static function form(): array
    {
        return [
            Step::make('Paciente')
                ->description('Datos del paciente')
                ->schema([
                    Select::make('paciente_id')
                        ->relationship('paciente', 'nombre')
                        ->getOptionLabelFromRecordUsing(fn(Paciente $record) => "{$record->nombre_completo}")
                        ->searchable(['nombres', 'numero_documento'])
                        ->createOptionForm([
                            Grid::make()
                                ->schema([...PacienteForm::form()])
                        ]),
                ])->columns(2),
            Step::make('Orden')
                ->description('Datos generales de la orden')
                ->schema([
                    TextInput::make('numero_orden')
                        ->numeric()
                        ->required(),
                    TextInput::make('medico')
                        ->maxLength(100)
                        ->required(),
                    Select::make('establecimiento_id')
                        ->visible(fn(Get $get): bool => $get('tipo_establecimiento'))
                        ->relationship('establecimiento', 'nombre')
                        ->required()
                        ->suffixAction(
                            Action::make('establecimiento_institucional')
                                ->icon('tabler-a-b-2')
                                ->action(function (Set $set) {
                                    $set('tipo_establecimiento', false);
                                })
                        ),
                    Hidden::make('tipo_establecimiento')->default(true),
                    TextInput::make('establecimiento_otro')
                        ->label('Otro Establecimiento')
                        ->visible(fn(Get $get): bool => !$get('tipo_establecimiento'))
                        ->required()
                        ->suffixAction(
                            Action::make('establecimiento_otro')
                                ->icon('tabler-a-b-2')
                                ->action(function (Set $set) {
                                    $set('tipo_establecimiento', true);
                                })
                        ),
                    TextInput::make('diagnostico')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('CI10')
                        ->maxLength(255)
                        ->default(null),
                    TextInput::make('CPN')
                        ->maxLength(255)
                        ->default(null),
                    TextInput::make('EG')
                        ->maxLength(255)
                        ->default(null),
                    TextInput::make('codigo_atencion')
                        ->required()
                        ->maxLength(255),
                    DatePicker::make('fecha_registro')
                        ->default(now())
                        ->required(),

                ])
                ->columns(2),
            Step::make('Exámenes')
                ->description('Elegir examenes a realizar')
                ->schema([
                    Combobox::make('examens')
                        ->hiddenLabel()
                        ->minItems(1)
                        ->relationship('examens', 'nombre')
                        ->boxSearchs()
                ]),
        ];
    }

}