<?php

namespace App\Filament\Admin\Resources\OrdenResource\Pages;

use App\Filament\Admin\Resources\OrdenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrdens extends ListRecords
{
    protected static string $resource = OrdenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
