<?php

namespace App\Filament\Admin\Resources\ItemResource\Pages;

use App\Enums\Categoria\TipoCategoriaEnum;
use App\Enums\Examen\TipoExamenEnum;
use App\Filament\Admin\Resources\ItemResource;
use App\Filament\Admin\Resources\ItemResource\Forms\ItemForm;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Illuminate\Database\Eloquent\Builder;

class EditItem extends EditRecord
{
    use NestedPage;
    protected static string $resource = ItemResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            ...ItemForm::form(),
        ]);

    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
