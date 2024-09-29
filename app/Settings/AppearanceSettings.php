<?php

namespace App\Settings;

use App\Enums\Setting\AppColor;
use App\Enums\Setting\Font;
use App\Enums\Setting\RecordsPerPage;
use App\Enums\Setting\TableSortDirection;
use Spatie\LaravelSettings\Settings;

class AppearanceSettings extends Settings
{
    public Font $font;
    public TableSortDirection $table_sort_direction;
    public RecordsPerPage $records_per_page;
    public AppColor $danger;
    public AppColor $gray;
    public AppColor $info;
    public AppColor $primary;
    public AppColor $success;
    public AppColor $warning;
    public static function group(): string
    {
        return 'appearance';
    }

}
