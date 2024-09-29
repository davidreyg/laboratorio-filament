<?php

use App\Enums\Setting\AppColor;
use App\Enums\Setting\Font;
use App\Enums\Setting\RecordsPerPage;
use App\Enums\Setting\TableSortDirection;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('appearance.font', Font::DEFAULT );
        $this->migrator->add('appearance.table_sort_direction', TableSortDirection::DEFAULT );
        $this->migrator->add('appearance.records_per_page', RecordsPerPage::DEFAULT );
        $this->migrator->add('appearance.primary', AppColor::Blue);
        $this->migrator->add('appearance.danger', AppColor::Red);
        $this->migrator->add('appearance.gray', AppColor::Gray);
        $this->migrator->add('appearance.info', AppColor::Indigo);
        $this->migrator->add('appearance.success', AppColor::Green);
        $this->migrator->add('appearance.warning', AppColor::Amber);
    }
};
