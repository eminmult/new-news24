<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainInfo extends Model
{
    protected $table = 'main_info';

    protected $fillable = [
        'site_name',
        'site_url',
        'site_title',
        'site_description',
        'address',
        'emails',
        'phones',
        'fax',
        'location',
        'reklam_phones',
        'reklam_emails',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'logo',
        'favicon',
    ];

    protected $casts = [
        'emails' => 'array',
        'phones' => 'array',
        'reklam_phones' => 'array',
        'reklam_emails' => 'array',
    ];

    /**
     * Get the singleton instance (with caching)
     */
    public static function getInstance(): ?self
    {
        return \Illuminate\Support\Facades\Cache::rememberForever('main_info', function () {
            return static::first();
        });
    }

    /**
     * Get or create the singleton instance
     */
    public static function getOrCreate(): self
    {
        $instance = static::first();

        if (!$instance) {
            $instance = static::create([
                'site_name' => 'OLAY.az',
                'site_url' => 'https://olay.az',
                'emails' => ['info@olay.az'],
                'phones' => ['+994'],
            ]);
        }

        return $instance;
    }

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        static::saved(function ($mainInfo) {
            // Очищаем только кеш MainInfo
            \Illuminate\Support\Facades\Cache::forget('main_info');
            // Очищаем view cache
            \Illuminate\Support\Facades\Artisan::call('view:clear');
        });
    }
}
