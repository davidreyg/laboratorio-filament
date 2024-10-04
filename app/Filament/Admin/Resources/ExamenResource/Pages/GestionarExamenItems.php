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
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Concerns\NestedRelationManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GestionarExamenItems extends ManageRelatedRecords
{
    use NestedPage;
    use NestedRelationManager;
    protected static string $resource = ExamenResource::class;

    protected static string $relationship = 'children';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Items';
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
                Tables\Actions\AssociateAction::make(),
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

    protected function configureCreateAction(Tables\Actions\CreateAction $action): void
    {
        $action
            ->authorize(static fn(ManageRelatedRecords $livewire): bool => $livewire->canCreate())
            ->form(fn(Form $form): Form => $this->form($form->columns(2)));
    }
}
