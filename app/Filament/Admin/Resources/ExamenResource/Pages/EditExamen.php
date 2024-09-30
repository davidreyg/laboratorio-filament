<?php

namespace App\Filament\Admin\Resources\ExamenResource\Pages;

use App\Filament\Admin\Resources\ExamenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExamen extends EditRecord
{
    protected static string $resource = ExamenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
