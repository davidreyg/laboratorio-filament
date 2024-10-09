<?php

namespace App\Filament\Admin\Resources\ExamenResource\Pages;

use App\Filament\Admin\Resources\ExamenResource;
use App\Filament\Admin\Resources\ItemResource\Forms\ItemForm;
use Filament\Actions;
use Filament\Forms\Form;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Pages\CreateRelatedRecord;

class CreateExamenItem extends CreateRelatedRecord
{
    use NestedPage;
    protected static string $resource = ExamenResource::class;
    protected static string $relationship = 'children';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("children", [
            'record' => $this->getOwnerRecord(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            ...ItemForm::form(),
        ]);

    }
}
