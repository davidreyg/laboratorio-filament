<?php

namespace App\Filament\Admin\Resources\ExamenResource\Pages;

use App\Filament\Admin\Resources\ExamenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExamens extends ListRecords
{
    protected static string $resource = ExamenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
