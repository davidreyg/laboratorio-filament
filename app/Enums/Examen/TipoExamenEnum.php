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
    public const DEFAULT = self::STRING->value;

    public function getLabel(): ?string
    {
        return (string) $this->value;
    }
}
