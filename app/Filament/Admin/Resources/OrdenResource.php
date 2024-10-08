<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrdenResource\Pages;
use App\Filament\Admin\Resources\OrdenResource\Pages\RegistrarResultados;
use App\Filament\Admin\Resources\OrdenResource\RelationManagers;
use App\Models\Orden;
use App\States\Orden\OrdenState;
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
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->formatStateUsing(fn(OrdenState $state) => $state->display()),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('resultados')->url(fn(Orden $record): string => RegistrarResultados::getUrl(['record' => $record])),
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
            'resultados' => Pages\RegistrarResultados::route('/{record}/resultados'),
        ];
    }
}
