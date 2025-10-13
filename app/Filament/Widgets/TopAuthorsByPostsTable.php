<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TopAuthorsByPostsTable extends TableWidget
{
    protected int | string | array $columnSpan = 6;
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return Auth::user()?->role === User::ROLE_ADMIN;
    }

    protected function getTableHeading(): string
    {
        return __('dashboard.tables.top_authors_by_posts');
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

        $query = User::query()
            ->where('role', User::ROLE_AUTHOR);

        $query->withCount(['posts' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('published_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        }]);

        return $table
            ->query(
                $query->orderBy('posts_count', 'desc')->limit(10)
            )
            ->columns([
                TextColumn::make('index')
                    ->label(__('dashboard.tables.number'))
                    ->rowIndex()
                    ->alignCenter(),

                TextColumn::make('name')
                    ->label(__('dashboard.tables.author'))
                    ->searchable()
                    ->weight('medium')
                    ->size('sm'),

                TextColumn::make('posts_count')
                    ->label(__('dashboard.tables.posts_count'))
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->size('sm'),
            ])
            ->paginated(false);
    }
}
