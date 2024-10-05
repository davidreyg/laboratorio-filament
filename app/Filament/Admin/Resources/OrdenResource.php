<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrdenResource\Pages;
use App\Filament\Admin\Resources\OrdenResource\RelationManagers;
use App\Models\Orden;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdenResource extends Resource
{
    protected static ?string $model = Orden::class;
    protected static ?string $pluralModelLabel = 'Ordenes de laboratorio';
    // protected static ?string $modelLabel = 'exámen';
    protected static ?string $navigationIcon = 'tabler-book-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('diagnostico')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('CI10')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('CPN')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('EG')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('codigo_atencion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('numero_orden')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('fecha_registro')
                    ->required(),
                Forms\Components\TextInput::make('paciente_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('establecimiento_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('user_id')
                    ->required(),
                Forms\Components\TextInput::make('registrador_id'),
                Forms\Components\TextInput::make('verificador_id'),
                Forms\Components\TextInput::make('establecimiento_otro')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('medico')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('estado')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('observaciones')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_atencion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_registro')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paciente.numero_documento')
                    ->label('N° de Documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paciente.nombre_completo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('medico')
                    ->searchable(),
                Tables\Columns\TextColumn::make('diagnostico')
                    ->searchable(),
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
            'index' => Pages\ListOrdens::route('/'),
            'create' => Pages\CreateOrden::route('/create'),
            'edit' => Pages\EditOrden::route('/{record}/edit'),
        ];
    }
}
