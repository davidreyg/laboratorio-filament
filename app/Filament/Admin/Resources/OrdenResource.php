<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrdenResource\Pages;
use App\Filament\Admin\Resources\OrdenResource\Pages\RegistrarResultados;
use App\Filament\Admin\Resources\OrdenResource\RelationManagers;
use App\Models\Orden;
use App\States\Orden\OrdenState;
use App\States\Orden\Registrado;
use App\States\Orden\Verificado;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
                Tables\Actions\Action::make('resultados')
                    ->hiddenLabel()
                    ->tooltip('Registrar Resultados')
                    ->icon('tabler-writing')
                    ->color('warning')
                    ->url(fn(Orden $record): string => RegistrarResultados::getUrl(['record' => $record])),
                Tables\Actions\Action::make('registrar')
                    ->hiddenLabel()
                    ->tooltip(fn(Orden $record): string => (new Registrado($record))->action())
                    ->visible(fn(Orden $record): bool => $record->estado->canTransitionTo(Registrado::class, null))
                    ->color(fn(Orden $record): string => (new Registrado($record))->color())
                    ->icon(fn(Orden $record): string => (new Registrado($record))->icon())
                    ->requiresConfirmation()
                    ->action(function (Orden $record) {
                        try {
                            $record->estado->transitionTo(Registrado::class, null);
                        } catch (\Throwable $th) {
                            Notification::make('Error')
                                ->danger()
                                ->body($th->getMessage())
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('Verificar')
                    ->hiddenLabel()
                    ->tooltip(fn(Orden $record): string => (new Verificado($record))->action())
                    ->visible(fn(Orden $record): bool => $record->estado->canTransitionTo(Verificado::class, null))
                    ->color(fn(Orden $record): string => (new Verificado($record))->color())
                    ->icon(fn(Orden $record): string => (new Verificado($record))->icon())
                    ->requiresConfirmation()
                    ->action(function (Orden $record) {
                        try {
                            $record->estado->transitionTo(Verificado::class, null);
                        } catch (\Throwable $th) {
                            Notification::make('Error')
                                ->danger()
                                ->body($th->getMessage())
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('imprimirOrden')
                    ->hiddenLabel()
                    ->tooltip('Imprimir Orden')
                    ->color('info')
                    ->icon('tabler-printer')
                    ->url(fn(Orden $record) => route('orden.pdf.detalle', ['orden' => $record]), true),
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Editar'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Eliminar'),
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
