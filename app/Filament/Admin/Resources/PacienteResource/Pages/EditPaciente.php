<?php

namespace App\Filament\Admin\Resources\PacienteResource\Pages;

use App\Filament\Admin\Resources\PacienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaciente extends EditRecord
{
    protected static string $resource = PacienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
