<?php

use App\Enums\Setting\DateFormat;
use App\Enums\Setting\TimeFormat;
use App\Enums\Setting\WeekStart;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('localization.date_format', DateFormat::DEFAULT );
        $this->migrator->add('localization.time_format', TimeFormat::DEFAULT );
        $this->migrator->add('localization.week_start', WeekStart::DEFAULT );
    }
};
