<?php

namespace App\Filament\Admin\Resources\Shield\RoleResource\Pages;

use App\Filament\Admin\Resources\Shield\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
