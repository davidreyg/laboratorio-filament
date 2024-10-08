<?php

namespace App\Enums\Unidad;

use App\Enums\Concerns\Utilities;
use Filament\Support\Contracts\HasLabel;

enum OperadoresEnum: string implements HasLabel
{
    use Utilities;
    case MENOR = '<';
    case MENOR_IGUAL = '<=';
    case MAYOR = '>';
    case MAYOR_IGUAL = '>=';
    // public const DEFAULT = self::MULTIVALOR->value;

    public function getLabel(): ?string
    {
        return (string) $this->name;
    }
}
