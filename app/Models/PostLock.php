<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostLock extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'locked_at',
        'last_heartbeat',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
        'last_heartbeat' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Проверяет, активна ли блокировка (последний heartbeat не старше 2 минут)
     */
    public function isActive(): bool
    {
        if (!$this->last_heartbeat) {
            return $this->locked_at->diffInMinutes(now()) < 2;
        }

        return $this->last_heartbeat->diffInMinutes(now()) < 2;
    }

    /**
     * Очищает неактивные блокировки (старше 2 минут без heartbeat)
     */
    public static function cleanupStale(): void
    {
        self::where('last_heartbeat', '<', now()->subMinutes(2))
            ->orWhere(function ($query) {
                $query->whereNull('last_heartbeat')
                    ->where('locked_at', '<', now()->subMinutes(2));
            })
            ->delete();
    }
}
