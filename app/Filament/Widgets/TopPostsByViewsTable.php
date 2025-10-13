<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class TopPostsByViewsTable extends TableWidget
{
    protected int | string | array $columnSpan = 6;
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return Auth::user()?->role === User::ROLE_ADMIN;
    }

    protected function getTableHeading(): string
    {
        return __('dashboard.tables.top_posts_by_views');
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

        $query = Post::query();
        $query->whereBetween('published_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);

        return $table
            ->query(
                $query->orderBy('views', 'desc')->limit(10)
            )
            ->columns([
                TextColumn::make('index')
                    ->label(__('dashboard.tables.number'))
                    ->rowIndex()
                    ->alignCenter(),

                TextColumn::make('title')
                    ->label(__('dashboard.tables.post'))
                    ->searchable()
                    ->weight('medium')
                    ->size('sm')
                    ->limit(50)
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab()
                    ->color('primary'),

                TextColumn::make('views')
                    ->label(__('dashboard.tables.views'))
                    ->alignCenter()
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', ' '))
                    ->size('sm'),
            ])
            ->paginated(false);
    }
}
