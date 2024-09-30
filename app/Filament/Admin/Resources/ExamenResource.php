<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Examen\TipoExamenEnum;
use App\Filament\Admin\Resources\ExamenResource\Pages;
use App\Filament\Admin\Resources\ExamenResource\RelationManagers;
use App\Models\Examen;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamenResource extends Resource
{
    protected static ?string $model = Examen::class;

    protected static ?string $navigationIcon = 'tabler-test-pipe';
    protected static ?string $navigationGroup = 'Mantenimiento';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('f')->schema([
                    Tab::make('Datos Generales')->schema([
                        Select::make('categoria_id')->relationship('categoria', 'nombre'),
                        Forms\Components\TextInput::make('codigo')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->maxLength(200),
                        Forms\Components\TextInput::make('precio')
                            ->numeric()
                            ->default(null),
                        Forms\Components\Toggle::make('is_active')
                            ->inline(false)
                            ->label('¿Activo?')
                            ->required(),
                        Select::make('tipo')
                            ->required()
                            ->disabledOn('edit')
                            ->options(TipoExamenEnum::class)
                            ->live(),
                    ])->columns(2),
                    Tab::make('Unidades')
                        ->visible(fn(Get $get) => $get('tipo') === TipoExamenEnum::UNIDAD->value)
                        ->schema([
                            Repeater::make('unidads')
                                ->hiddenLabel()
                                ->schema([

                                ]),
                        ])
                ])
            ])
            ->columns(null);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('parent_id', null))
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('precio')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categoria.nombre')
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

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditExamen::class,
            Pages\GestionarExamenUnidades::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExamens::route('/'),
            'create' => Pages\CreateExamen::route('/create'),
            'edit' => Pages\EditExamen::route('/{record}/edit'),
            'unidades' => Pages\GestionarExamenUnidades::route('/{record}/unidades'),
        ];
    }
}
