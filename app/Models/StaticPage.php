<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope для активных страниц
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Получить значение из content по ключу
     */
    public function getContent($key, $default = null)
    {
        return data_get($this->content, $key, $default);
    }

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        // Очищаем кеш статической страницы при любом изменении
        static::saved(function ($page) {
            \Illuminate\Support\Facades\Cache::forget("static_page_{$page->slug}");
        });

        static::deleted(function ($page) {
            \Illuminate\Support\Facades\Cache::forget("static_page_{$page->slug}");
        });
    }
}
