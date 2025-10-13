<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Config extends Model
{
    protected $fillable = [
        'key',
        'value',
        'label',
        'type',
        'description',
    ];

    /**
     * Get config value by key
     */
    public static function getValue(string $key, ?string $default = null): ?string
    {
        return Cache::remember("config.{$key}", 3600, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    /**
     * Set config value
     */
    public static function setValue(string $key, ?string $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("config.{$key}");
    }

    /**
     * Clear config cache
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        static::saved(function ($config) {
            Cache::forget("config.{$config->key}");
        });

        static::deleted(function ($config) {
            Cache::forget("config.{$config->key}");
        });
    }
}
