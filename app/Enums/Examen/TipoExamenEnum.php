<?php

namespace App\Enums\Examen;

use App\Enums\Concerns\Utilities;
use Filament\Support\Contracts\HasLabel;

enum TipoExamenEnum: string implements HasLabel
{
    use Utilities;
    case STRING = 'string';
    case UNIDAD = 'unidad';
    case RESPUESTA = 'respuesta';
    case PADRE = 'padre';
    public const DEFAULT = self::STRING->value;

    public function getLabel(): ?string
    {
        return (string) ucfirst($this->value);
    }

    public function getHijos(): array
    {
        return array_filter(TipoExamenEnum::cases(), fn($case) => $case !== TipoExamenEnum::PADRE);
    }
}
