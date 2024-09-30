<?php

namespace App\Filament\Admin\Resources\ExamenResource\Pages;

use App\Enums\Examen\TipoExamenEnum;
use App\Filament\Admin\Resources\ExamenResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GestionarExamenRespuestas extends ManageRelatedRecords
{
    protected static string $resource = ExamenResource::class;

    protected static string $relationship = 'respuestas';

    protected static ?string $navigationIcon = 'tabler-question-mark';

    public static function getNavigationLabel(): string
    {
        return 'Respuestas';
    }

    protected function authorizeAccess(): void
    {
        abort_unless($this->getRecord()->tipo === TipoExamenEnum::RESPUESTA->value, 403);
        abort_unless(static::canAccess(['record' => $this->getRecord()]), 403);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('nombre'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()->preloadRecordSelect(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function shouldRegisterNavigation($parameters = []): bool
    {
        return $parameters['record']->tipo === TipoExamenEnum::RESPUESTA->value;
    }
}
