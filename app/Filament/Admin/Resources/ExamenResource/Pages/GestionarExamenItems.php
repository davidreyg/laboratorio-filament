<?php

namespace App\Filament\Admin\Resources\ExamenResource\Pages;

use App\Enums\Examen\TipoExamenEnum;
use App\Filament\Admin\Resources\ExamenResource;
use App\Filament\Admin\Resources\ItemResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Concerns\NestedRelationManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GestionarExamenItems extends ManageRelatedRecords
{
    use NestedPage;
    use NestedRelationManager;
    protected static string $resource = ExamenResource::class;

    protected static string $relationship = 'children';

    protected static ?string $navigationIcon = 'tabler-flask';

    public static function getNavigationLabel(): string
    {
        return 'Items';
    }

    protected function configureEditAction(Tables\Actions\EditAction $action): void
    {
        parent::configureEditAction($action);

        $action->url(
            fn(Model $record) => ItemResource::getUrl(
                'edit',
                ['record' => $record],
            )
        );
    }

    protected function configureCreateAction(CreateAction $action): void
    {

        parent::configureCreateAction($action->url(
            fn() => ExamenResource::getUrl("children.create", [
                'record' => $this->getOwnerRecord(),
            ])
        ));
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
                Tables\Columns\TextColumn::make('codigo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                // Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function shouldRegisterNavigation($parameters = []): bool
    {
        return $parameters['record']->tipo === TipoExamenEnum::PADRE->value;
    }
}
