<?php

namespace App\Filament\Admin\Resources\ExamenResource\Pages;

use App\Filament\Admin\Resources\ExamenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class CreateExamen extends CreateRecord
{
    use NestedPage;
    protected static string $resource = ExamenResource::class;
}
