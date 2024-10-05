<?php

namespace App\Filament\Admin\Resources\PacienteResource\Pages;

use App\Filament\Admin\Resources\PacienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPacientes extends ListRecords
{
    protected static string $resource = PacienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
