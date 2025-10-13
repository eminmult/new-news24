<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DateRangeFilter;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TopAuthorsByPostsTable;
use App\Filament\Widgets\TopAuthorsByViewsTable;
use App\Filament\Widgets\TopPostsByViewsTable;
use App\Filament\Widgets\TopCategoriesByViewsTable;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public static function getNavigationLabel(): string
    {
        return __('dashboard.navigation_label');
    }

    public function getTitle(): string
    {
        return __('dashboard.title');
    }

    public function getWidgets(): array
    {
        return [
            DateRangeFilter::class,
            StatsOverview::class,
            TopPostsByViewsTable::class,
            TopCategoriesByViewsTable::class,
            TopAuthorsByPostsTable::class,
            TopAuthorsByViewsTable::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 12;
    }
}
