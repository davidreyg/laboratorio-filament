<?php

namespace App\Filament\Admin\Resources\ExamenResource\Pages;

use App\Filament\Admin\Resources\ExamenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class ListExamens extends ListRecords
{
    use NestedPage;
    protected static string $resource = ExamenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
