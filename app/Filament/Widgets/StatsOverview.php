<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    // Отключаем кеширование виджета
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return Auth::user()?->role === User::ROLE_ADMIN;
    }

    // Добавляем публичные свойства для реактивности
    public ?string $filterStartDate = null;
    public ?string $filterEndDate = null;

    public function mount(): void
    {
        $this->updateDates();
    }

    protected function updateDates(): void
    {
        // Если свойства уже установлены, не перезаписываем их
        if ($this->filterStartDate && $this->filterEndDate) {
            return;
        }

        $startDate = request()->query('start_date');
        $endDate = request()->query('end_date');

        // Если даты не указаны, показываем последние 7 дней по умолчанию
        if (!$startDate || !$endDate) {
            $startDate = now()->subDays(6)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $this->filterStartDate = $startDate;
        $this->filterEndDate = $endDate;
    }

    // Отключаем кеширование stats
    protected function getCachedStats(): array
    {
        return $this->getStats();
    }

    protected function getStats(): array
    {
        // Обновляем даты при каждом вызове getStats
        $this->updateDates();

        $startDate = $this->filterStartDate;
        $endDate = $this->filterEndDate;

        $postsQuery = Post::query();
        $postsQuery->whereBetween('published_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);

        $count = $postsQuery->count();

        $description = __('dashboard.stats.period') . ': ' . date('d.m.Y', strtotime($startDate)) . ' - ' . date('d.m.Y', strtotime($endDate));

        return [
            Stat::make(__('dashboard.stats.total_posts'), $count)
                ->description($description)
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make(__('dashboard.stats.categories'), Category::count())
                ->description(__('dashboard.stats.total_categories'))
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('info'),

            Stat::make(__('dashboard.stats.authors'), User::where('role', User::ROLE_AUTHOR)->count())
                ->description(__('dashboard.stats.active_authors'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}
