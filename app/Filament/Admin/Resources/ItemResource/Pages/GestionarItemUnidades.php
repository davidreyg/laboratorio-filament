<?php

namespace App\Filament\Admin\Resources\ItemResource\Pages;

use App\Enums\Examen\TipoExamenEnum;
use App\Enums\Unidad\OperadoresEnum;
use App\Enums\Unidad\TipoUnidadEnum;
use App\Filament\Admin\Resources\ItemResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GestionarItemUnidades extends ManageRelatedRecords
{
    use NestedPage;
    protected static string $resource = ItemResource::class;

    protected static string $relationship = 'unidads';

    protected static ?string $navigationIcon = 'tabler-ruler-measure';

    public static function getNavigationLabel(): string
    {
        return 'Unidades';
    }

    protected function authorizeAccess(): void
    {
        abort_unless($this->getRecord()->tipo === TipoExamenEnum::UNIDAD->value, 403);
        abort_unless(static::canAccess(['record' => $this->getRecord()]), 403);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ...$this->pivotForm()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('nombre'),
                Tables\Columns\TextColumn::make('minimo'),
                Tables\Columns\TextColumn::make('maximo'),
                Tables\Columns\TextColumn::make('tipo'),
                Tables\Columns\TextColumn::make('operador'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['nombre'])
                    ->modalWidth(MaxWidth::FiveExtraLarge)
                    ->form(fn(AttachAction $action): array => [
                        Group::make([
                            $action->getRecordSelect()->label('Unidad')->hiddenLabel(false),
                            ...$this->pivotForm(),
                        ])->columns(2)
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function pivotForm(): array
    {
        return [
            Select::make('tipo')
                ->required()
                ->options(TipoUnidadEnum::class)
                ->live(),
            Group::make([
                Forms\Components\Select::make('operador')
                    ->options(OperadoresEnum::class)
                    ->required()
                    ->visible(fn(Get $get) => $get('tipo') === TipoUnidadEnum::OPERADOR->value),
                Forms\Components\TextInput::make('minimo')
                    ->numeric()
                    ->required()
                    ->visible(fn(Get $get) => $get('tipo'))
                    ->minValue(0.1),
                Forms\Components\TextInput::make('maximo')
                    ->numeric()
                    ->required()
                    ->visible(fn(Get $get) => $get('tipo') === TipoUnidadEnum::MULTIVALOR->value)
                    ->minValue(fn(Get $get) => ($get('minimo') ? $get('minimo') + 0.1 : 0.2)),
            ])->columns(3)->columnSpanFull()
        ];
    }

    public static function shouldRegisterNavigation($parameters = []): bool
    {
        return $parameters['record']->tipo === TipoExamenEnum::UNIDAD->value;
    }
}
