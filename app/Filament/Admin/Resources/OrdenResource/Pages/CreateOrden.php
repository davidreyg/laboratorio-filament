<?php

namespace App\Filament\Admin\Resources\OrdenResource\Pages;

use App\Filament\Admin\Resources\OrdenResource;
use App\Filament\Admin\Resources\OrdenResource\Forms\OrdenSteps;
use App\Filament\Admin\Resources\PacienteResource\Forms\PacienteForm;
use App\Models\Paciente;
use Filament\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Novadaemon\FilamentCombobox\Combobox;
use Str;

class CreateOrden extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = OrdenResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        //TODO: Manejar estados
        $data['estado'] = 0;

        return $data;
    }

    protected function getSteps(): array
    {
        return [
            ...OrdenSteps::form(),
        ];
    }
}
