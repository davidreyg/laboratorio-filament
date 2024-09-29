<?php

namespace App\Enums\Grupo;

use App\Enums\Concerns\Utilities;
use Filament\Support\Contracts\HasLabel;

enum TipoPreguntaEnum: string implements HasLabel
{
    use Utilities;
    case SIMPLE = 'Simple';
    case MULTIPLE = 'Multiple';
    public const DEFAULT = self::SIMPLE->value;

    public function getLabel(): ?string
    {
        return (string) $this->value;
    }
}
