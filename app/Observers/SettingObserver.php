<?php

namespace App\Observers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingObserver
{
    /**
     * Handle the Setting "created" event.
     */
    public function created(Setting $setting): void
    {
        $this->clearRelatedCaches($setting);
    }

    /**
     * Handle the Setting "updated" event.
     */
    public function updated(Setting $setting): void
    {
        $this->clearRelatedCaches($setting);
    }

    /**
     * Handle the Setting "deleted" event.
     */
    public function deleted(Setting $setting): void
    {
        $this->clearRelatedCaches($setting);
    }

    /**
     * Handle the Setting "restored" event.
     */
    public function restored(Setting $setting): void
    {
        $this->clearRelatedCaches($setting);
    }

    /**
     * Очистка кешей, зависящих от настроек
     */
    protected function clearRelatedCaches(Setting $setting): void
    {
        // Очищаем кеш самой настройки
        Cache::forget("setting_{$setting->name}");

        // Очищаем кеши, которые зависят от конкретных настроек
        $settingsToClear = [
            'slider_posts_count' => ['home_slider_posts', 'home_main_featured_posts'],
            'trending_posts_count' => ['home_important_posts'],
            'home_posts_count' => function() {
                // Очищаем все страницы последних постов
                for ($i = 1; $i <= 20; $i++) {
                    Cache::forget("home_latest_posts_page_{$i}");
                }
            },
            'category_posts_count' => function() {
                // Очищаем все страницы всех категорий
                $categories = \App\Models\Category::all();
                foreach ($categories as $category) {
                    for ($i = 1; $i <= 50; $i++) {
                        Cache::forget("category_{$category->id}_posts_page_{$i}");
                    }
                }
            },
        ];

        if (isset($settingsToClear[$setting->name])) {
            $toClear = $settingsToClear[$setting->name];

            if (is_callable($toClear)) {
                // Если это функция - вызываем её
                $toClear();
            } elseif (is_array($toClear)) {
                // Если это массив - очищаем каждый ключ
                foreach ($toClear as $cacheKey) {
                    Cache::forget($cacheKey);
                }
            }
        }

        // Очищаем Response Cache (Full Page Cache) при любом изменении настроек
        if (class_exists('\Spatie\ResponseCache\Facades\ResponseCache')) {
            \Spatie\ResponseCache\Facades\ResponseCache::clear();
        }
    }
}
