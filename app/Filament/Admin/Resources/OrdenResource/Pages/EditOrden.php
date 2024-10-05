<?php

namespace App\Filament\Admin\Resources\OrdenResource\Pages;

use App\Filament\Admin\Resources\OrdenResource;
use App\Filament\Admin\Resources\OrdenResource\Forms\OrdenSteps;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrden extends EditRecord
{
    use EditRecord\Concerns\HasWizard;
    protected static string $resource = OrdenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['tipo_establecimiento'] = isset($data['establecimiento_id']);

        return $data;
    }

    protected function getSteps(): array
    {
        return [
            ...OrdenSteps::form(),
        ];
    }
}
