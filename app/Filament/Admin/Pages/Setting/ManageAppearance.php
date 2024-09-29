<?php

namespace App\Filament\Admin\Pages\Setting;

use App\Enums\Setting\AppColor;
use App\Enums\Setting\Font;
use App\Enums\Setting\RecordsPerPage;
use App\Enums\Setting\TableSortDirection;
use App\Settings\AppearanceSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;

use function Filament\Support\is_app_url;

class ManageAppearance extends SettingsPage
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'tabler-palette';
    protected static ?int $navigationSort = 2;
    protected static string $settings = AppearanceSettings::class;

    // protected static ?string $cluster = Settings::class;

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                $this->getGeneralSection(),
                $this->getPaletteSection(),
                $this->getDataPresentationSection(),
            ]);
    }

    protected function getGeneralSection(): Component
    {
        return Section::make('General')
            ->schema([
                Select::make('font')
                    ->label('Tipo de letra')
                    ->allowHtml()
                    ->native(false)
                    ->options(
                        collect(Font::cases())
                            ->mapWithKeys(static fn($case) => [
                                $case->value => "<span style='font-family:{$case->getLabel()}'>{$case->getLabel()}</span>",
                            ]),
                    ),

            ])->columns();
    }
    protected function getPaletteSection(): Component
    {
        return Section::make('Paleta de colores')
            ->schema([
                ...$this->buildSiteTheme(),
            ])->columns();
    }

    public function buildSiteTheme(): array
    {

        return [
            Select::make('primary')
                ->allowHtml()
                ->native(false)
                ->options(
                    collect(AppColor::cases())
                        ->sort(static fn($a, $b) => $a->value <=> $b->value)
                        ->mapWithKeys(static fn($case) => [
                            $case->value => "<span class='flex items-center gap-x-4'>
                            <span class='rounded-full w-4 h-4' style='background:rgb(" . $case->getColor()[600] . ")'></span>
                            <span>" . $case->getLabel() . '</span>
                            </span>',
                        ]),
                ),
            Select::make('danger')
                ->allowHtml()
                ->native(false)
                ->options(
                    collect(AppColor::cases())
                        ->sort(static fn($a, $b) => $a->value <=> $b->value)
                        ->mapWithKeys(static fn($case) => [
                            $case->value => "<span class='flex items-center gap-x-4'>
                            <span class='rounded-full w-4 h-4' style='background:rgb(" . $case->getColor()[600] . ")'></span>
                            <span>" . $case->getLabel() . '</span>
                            </span>',
                        ]),
                ),
            Select::make('gray')
                ->allowHtml()
                ->native(false)
                ->options(
                    collect(AppColor::cases())
                        ->sort(static fn($a, $b) => $a->value <=> $b->value)
                        ->mapWithKeys(static fn($case) => [
                            $case->value => "<span class='flex items-center gap-x-4'>
                            <span class='rounded-full w-4 h-4' style='background:rgb(" . $case->getColor()[600] . ")'></span>
                            <span>" . $case->getLabel() . '</span>
                            </span>',
                        ]),
                ),
            Select::make('info')
                ->allowHtml()
                ->native(false)
                ->options(
                    collect(AppColor::cases())
                        ->sort(static fn($a, $b) => $a->value <=> $b->value)
                        ->mapWithKeys(static fn($case) => [
                            $case->value => "<span class='flex items-center gap-x-4'>
                            <span class='rounded-full w-4 h-4' style='background:rgb(" . $case->getColor()[600] . ")'></span>
                            <span>" . $case->getLabel() . '</span>
                            </span>',
                        ]),
                ),
            Select::make('success')
                ->allowHtml()
                ->native(false)
                ->options(
                    collect(AppColor::cases())
                        ->sort(static fn($a, $b) => $a->value <=> $b->value)
                        ->mapWithKeys(static fn($case) => [
                            $case->value => "<span class='flex items-center gap-x-4'>
                            <span class='rounded-full w-4 h-4' style='background:rgb(" . $case->getColor()[600] . ")'></span>
                            <span>" . $case->getLabel() . '</span>
                            </span>',
                        ]),
                ),
            Select::make('warning')
                ->allowHtml()
                ->native(false)
                ->options(
                    collect(AppColor::cases())
                        ->sort(static fn($a, $b) => $a->value <=> $b->value)
                        ->mapWithKeys(static fn($case) => [
                            $case->value => "<span class='flex items-center gap-x-4'>
                            <span class='rounded-full w-4 h-4' style='background:rgb(" . $case->getColor()[600] . ")'></span>
                            <span>" . $case->getLabel() . '</span>
                            </span>',
                        ]),
                ),
        ];
    }

    protected function getDataPresentationSection(): Component
    {
        return Section::make('Data Presentation')
            ->schema([
                Select::make('table_sort_direction')
                    ->options(TableSortDirection::class),
                Select::make('records_per_page')
                    ->options(RecordsPerPage::class),
            ])->columns();
    }


    public function save(AppearanceSettings $settings = null): void
    {
        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $settings->fill($data);
            $settings->save();

            $this->callHook('afterSave');

            $this->sendSuccessNotification('Apariencia actualizada.');

            $this->redirect(static::getUrl(), navigate: FilamentView::hasSpaMode() && is_app_url(static::getUrl()));
        } catch (\Throwable $th) {
            $this->sendErrorNotification('Failed to update settings. ' . $th->getMessage());
            throw $th;
        }
    }

    public function sendSuccessNotification($title)
    {
        Notification::make()
            ->title($title)
            ->success()
            ->send();
    }

    public function sendErrorNotification($title)
    {
        Notification::make()
            ->title($title)
            ->danger()
            ->send();
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.settings");
    }

    public static function getNavigationLabel(): string
    {
        return 'Apariencia';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Apariencia';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Apariencia';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Gestionar la configuracion de la apariencia';
    }
}
