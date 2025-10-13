<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;

class TopCategoriesByViewsTable extends TableWidget
{
    protected int | string | array $columnSpan = 6;
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return Auth::user()?->role === User::ROLE_ADMIN;
    }

    protected function getTableHeading(): string
    {
        return __('dashboard.tables.top_categories_by_views');
    }

    public function table(Table $table): Table
    {
        $startDate = request()->query('start_date');
        $endDate = request()->query('end_date');

        // Если даты не указаны, показываем последние 7 дней по умолчанию
        if (!$startDate || !$endDate) {
            $startDate = now()->subDays(6)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $query = Category::query()
            ->select('categories.id', 'categories.name', DB::raw('COALESCE(SUM(posts.views), 0) as total_views'))
            ->leftJoin('category_post', 'categories.id', '=', 'category_post.category_id')
            ->leftJoin('posts', function($join) use ($startDate, $endDate) {
                $join->on('category_post.post_id', '=', 'posts.id');
                $join->whereBetween('posts.published_at', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_views', 'desc')
            ->limit(10);

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('index')
                    ->label(__('dashboard.tables.number'))
                    ->rowIndex()
                    ->alignCenter(),

                TextColumn::make('name')
                    ->label(__('dashboard.tables.category'))
                    ->searchable()
                    ->weight('medium')
                    ->size('sm'),

                TextColumn::make('total_views')
                    ->label(__('dashboard.tables.views'))
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', ' '))
                    ->size('sm'),
            ])
            ->paginated(false);
    }
}
