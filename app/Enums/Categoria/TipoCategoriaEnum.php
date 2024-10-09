<?php

namespace App\Enums\Categoria;

use App\Enums\Concerns\Utilities;
use Filament\Support\Contracts\HasLabel;

enum TipoCategoriaEnum: string implements HasLabel
{
    use Utilities;
    case EXAMEN = 'examen';
    case ITEM = 'item';
    public const DEFAULT = self::EXAMEN->value;

    public function getLabel(): ?string
    {
        return (string) ucfirst($this->value);
    }
}
