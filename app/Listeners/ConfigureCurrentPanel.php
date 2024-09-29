<?php

namespace App\Listeners;

use App\Enums\Setting\DateFormat;
use App\Enums\Setting\Font;
use App\Enums\Setting\RecordsPerPage;
use App\Enums\Setting\TableSortDirection;
use App\Enums\Setting\TimeFormat;
use App\Enums\Setting\WeekStart;
use App\Events\PanelConfigured;
use App\Settings\AppearanceSettings;
use App\Settings\GeneralSettings;
use App\Settings\LocalizationSettings;
use App\Settings\MailSettings;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ConfigureCurrentPanel
{
    private GeneralSettings $generalSettings;
    private MailSettings $mailSettings;
    // private CurrencySettings $currencySettings;
    private AppearanceSettings $appearanceSettings;
    private LocalizationSettings $localizationSettings;

    /**
     * Create the event listener.
     */
    public function __construct(
        GeneralSettings $generalSettings,
        MailSettings $mailSettings,
        // CurrencySettings $currencySettings,
        AppearanceSettings $appearanceSettings,
        LocalizationSettings $localizationSettings
    ) {
        $this->generalSettings = $generalSettings;
        $this->mailSettings = $mailSettings;
        // $this->currencySettings = $currencySettings;
        $this->appearanceSettings = $appearanceSettings;
        $this->localizationSettings = $localizationSettings;
    }

    /**
     * Handle the event.
     */
    public function handle(PanelConfigured $event): void
    {
        $paginationPageOptions = RecordsPerPage::caseValues();
        $defaultPaginationPageOption = $this->appearanceSettings->records_per_page->value ?? RecordsPerPage::DEFAULT;
        $defaultSort = $this->appearanceSettings->table_sort_direction->value ?? TableSortDirection::DEFAULT;
        $dateFormat = $this->localizationSettings->date_format->value ?? DateFormat::DEFAULT;
        $timeFormat = $this->localizationSettings->time_format->value ?? TimeFormat::DEFAULT;
        $weekStart = $this->localizationSettings->week_start->value ?? WeekStart::DEFAULT;
        app()->setLocale('es');

        Notification::configureUsing(function (Notification $notification): void {
            $notification
                ->seconds(2.2);
        });

        Filament::getCurrentPanel()
            ->font($this->appearanceSettings->font->value ?? Font::DEFAULT )
            ->brandName($this->generalSettings->brand_name)
            ->brandLogo(\Storage::url($this->generalSettings->brand_logo))
            ->brandLogoHeight(\Storage::url($this->generalSettings->brand_logoHeight))
            ->favicon(\Storage::url($this->generalSettings->site_favicon))
            // ->colors($this->appearanceSettings->site_theme)
        ;
        FilamentColor::register([
            'primary' => $this->appearanceSettings->primary->getColor(),
            'danger' => $this->appearanceSettings->danger->getColor(),
            'gray' => $this->appearanceSettings->gray->getColor(),
            'info' => $this->appearanceSettings->info->getColor(),
            'success' => $this->appearanceSettings->success->getColor(),
            'warning' => $this->appearanceSettings->warning->getColor(),
        ]);
        Table::configureUsing(static function (Table $table) use ($dateFormat, $timeFormat, $paginationPageOptions, $defaultSort, $defaultPaginationPageOption): void {
            Table::$defaultDateDisplayFormat = $dateFormat;
            Table::$defaultTimeDisplayFormat = $timeFormat;
            $table
                ->paginationPageOptions($paginationPageOptions)
                ->defaultSort(column: 'id', direction: $defaultSort)
                ->defaultPaginationPageOption($defaultPaginationPageOption)
                ->extremePaginationLinks();
        }, isImportant: true);
        DatePicker::configureUsing(static function (DatePicker $component) use ($dateFormat, $weekStart) {
            $component
                ->displayFormat($dateFormat)
                ->firstDayOfWeek($weekStart);
        });
        TimePicker::configureUsing(static function (TimePicker $component) use ($timeFormat) {
            $component
                ->displayFormat($timeFormat);
        });
    }
}
