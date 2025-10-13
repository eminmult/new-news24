<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SearchQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'count',
    ];

    /**
     * Записать поисковый запрос
     */
    public static function recordQuery(string $query): void
    {
        $query = trim($query);

        if (empty($query)) {
            return;
        }

        // Проверяем, существует ли запрос за последние 24 часа
        $existing = self::where('query', $query)
            ->where('created_at', '>=', now()->subDay())
            ->first();

        if ($existing) {
            $existing->increment('count');
        } else {
            self::create([
                'query' => $query,
                'count' => 1,
            ]);
        }
    }

    /**
     * Получить топ поисковых запросов за последний месяц
     */
    public static function getTopQueries(int $limit = 5): array
    {
        return self::where('created_at', '>=', now()->subMonth())
            ->select('query', DB::raw('SUM(count) as total'))
            ->groupBy('query')
            ->orderByDesc('total')
            ->limit($limit)
            ->pluck('query')
            ->toArray();
    }
}
