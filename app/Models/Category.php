<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
        'order',
        'is_active',
        'show_in_menu',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        // Очищаем кеш категорий при любом изменении
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('menu_categories');
            \App\Http\Controllers\SitemapController::clearCache();
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('menu_categories');
            \App\Http\Controllers\SitemapController::clearCache();
        });
    }
}
