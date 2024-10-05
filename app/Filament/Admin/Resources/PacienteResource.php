<?php

namespace App\Filament\Admin\Resources;

use App\Actions\BuscarReniec;
use App\Filament\Admin\Resources\PacienteResource\Pages;
use App\Filament\Admin\Resources\PacienteResource\RelationManagers;
use App\Models\Paciente;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component as Livewire;
class PacienteResource extends Resource
{
    protected static ?string $model = Paciente::class;
    protected static ?string $navigationGroup = 'Mantenimiento';
    protected static ?string $navigationIcon = 'tabler-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tipo_documento_id')
                    ->required()
                    ->relationship('tipoDocumento', 'nombre')
                    ->live(),
                Forms\Components\TextInput::make('numero_documento')
                    ->required()
                    ->numeric()
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
                Forms\Components\TextInput::make('nombres')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('apellido_paterno')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('apellido_materno')
                    ->required()
                    ->maxLength(50),
                Forms\Components\DatePicker::make('fecha_nacimiento')
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
                Forms\Components\TextInput::make('edad')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sexo')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('direccion')
                    ->required()
                    ->maxLength(200),
                Forms\Components\TextInput::make('telefono')
                    ->tel()
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('historia_clinica')
                    ->required()
                    ->maxLength(50),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellido_paterno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellido_materno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_documento')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_nacimiento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('edad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sexo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('historia_clinica')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_documento_id')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPacientes::route('/'),
            'create' => Pages\CreatePaciente::route('/create'),
            'edit' => Pages\EditPaciente::route('/{record}/edit'),
        ];
    }
}
