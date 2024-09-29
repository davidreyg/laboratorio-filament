<?php

namespace App\Enums\Concerns;

trait Utilities
{
    public static function caseValues(): array
    {
        return array_column(static::cases(), 'value');
    }

    public static function caseNames(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function toArray(): array
    {
        $array = [];
        foreach (static::cases() as $case) {
            $array[$case->value] = $case->value;
        }
        return $array;
    }
}
