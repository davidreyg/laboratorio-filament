<?php

namespace App\Settings;

use App\Enums\Setting\DateFormat;
use App\Enums\Setting\TimeFormat;
use App\Enums\Setting\WeekStart;
use Spatie\LaravelSettings\Settings;

class LocalizationSettings extends Settings
{
    public DateFormat $date_format;
    public TimeFormat $time_format;
    public WeekStart $week_start;
    public static function group(): string
    {
        return 'localization';
    }

}
