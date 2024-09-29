<?php

namespace App\Enums\Criterio;

use App\Enums\Concerns\Utilities;
use Filament\Support\Contracts\HasLabel;

enum TipoCriterioEnum: string implements HasLabel
{
    use Utilities;
    case IGUAL = '=';
    case MAYOR_QUE = '>';
    case MAYOR_IGUAL_QUE = '>=';
    case MENOR_QUE = '<';
    case MENOR_IGUAL_QUE = '<=';
    case RANGO = 'RANGO';
    case ID = 'ID';
    // public const DEFAULT = self::SIMPLE->value;

    public function getLabel(): ?string
    {
        return (string) str(strtolower($this->name))->headline();
    }
}
