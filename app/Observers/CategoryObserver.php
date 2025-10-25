<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        $this->clearCategoryCaches($category);
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        $this->clearCategoryCaches($category);
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        $this->clearCategoryCaches($category);
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        $this->clearCategoryCaches($category);
    }

    /**
     * Очистка всех кешей связанных с категорией
     */
    protected function clearCategoryCaches(Category $category): void
    {
        // Очищаем кеш самой категории
        Cache::forget("category_{$category->slug}");
        Cache::forget("category_{$category->id}_total_views");
        Cache::forget("category_{$category->id}_today_posts_count");

        // Очищаем все страницы постов в категории
        for ($i = 1; $i <= 50; $i++) {
            Cache::forget("category_{$category->id}_posts_page_{$i}");
        }

        // Очищаем общие кеши категорий
        Cache::forget('all_categories');
        Cache::forget('menu_categories');
        Cache::forget('categories_with_posts_count');

        // Очищаем Response Cache (Full Page Cache)
        if (class_exists('\Spatie\ResponseCache\Facades\ResponseCache')) {
            \Spatie\ResponseCache\Facades\ResponseCache::clear();
        }
    }
}
