<?php

namespace App\Enums\Unidad;

use App\Enums\Concerns\Utilities;
use Filament\Support\Contracts\HasLabel;

enum TipoUnidadEnum: string implements HasLabel
{
    use Utilities;
    case MULTIVALOR = 'multivalor';
    case UNICO = 'unico';
    case OPERADOR = 'operador';
    public const DEFAULT = self::MULTIVALOR->value;

    public function getLabel(): ?string
    {
        return (string) $this->value;
    }
}
