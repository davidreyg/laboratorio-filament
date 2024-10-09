<?php

namespace App\Filament\Admin\Resources\ItemResource\Forms;
use App\Enums\Categoria\TipoCategoriaEnum;
use App\Enums\Examen\TipoExamenEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;
class ItemForm
{
    public static function form(): array
    {
        return [
            Select::make('categoria_id')->relationship(
                'categoria',
                'nombre',
                fn(Builder $query) => $query->where('tipo', TipoCategoriaEnum::ITEM->value),
            ),
            TextInput::make('codigo')
                ->required()
                ->unique('examens', 'codigo', ignoreRecord: true)
                ->numeric(),
            TextInput::make('nombre')
                ->required()
                ->maxLength(200),
            Select::make('tipo')
                ->required()
                ->disabledOn('edit')
                ->options(TipoExamenEnum::class)
                ->live(),
            Toggle::make('is_active')
                ->inline(false)
                ->label('¿Activo?')
                ->required(),
        ];
    }

}