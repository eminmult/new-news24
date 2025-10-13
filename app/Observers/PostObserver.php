<?php

namespace App\Observers;

use App\Http\Controllers\SitemapController;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        // Автоматически устанавливаем первую картинку галереи как главную, если главная не установлена
        if (!$post->featured_media_id) {
            $firstGalleryImage = $post->getFirstMedia('post-gallery');
            if ($firstGalleryImage) {
                $post->featured_media_id = $firstGalleryImage->id;
                $post->saveQuietly(); // Сохраняем без триггера observer, чтобы избежать рекурсии
            }
        }

        // Очищаем кеш sitemap и страниц
        SitemapController::clearCache();
        $this->clearPostCaches($post);
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // Автоматически устанавливаем первую картинку галереи как главную, если главная не установлена
        if (!$post->featured_media_id) {
            $firstGalleryImage = $post->getFirstMedia('post-gallery');
            if ($firstGalleryImage) {
                $post->featured_media_id = $firstGalleryImage->id;
                $post->saveQuietly(); // Сохраняем без триггера observer, чтобы избежать рекурсии
            }
        }

        // Очищаем кеш sitemap и страниц
        SitemapController::clearCache();
        $this->clearPostCaches($post);
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        // Очищаем кеш sitemap при удалении поста
        SitemapController::clearCache();
        $this->clearPostCaches($post);
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        // Очищаем кеш sitemap при восстановлении поста
        SitemapController::clearCache();
        $this->clearPostCaches($post);
    }

    /**
     * Очистка всех кешей связанных с постом
     */
    protected function clearPostCaches(Post $post): void
    {
        // Очищаем кеш главной страницы
        Cache::forget('home_slider_posts');
        Cache::forget('home_important_posts');
        Cache::forget('home_media_posts');

        // Очищаем кеш последних постов (все страницы)
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("home_latest_posts_page_{$i}");
        }

        // Очищаем кеш поста
        if ($post->slug && $post->category) {
            Cache::forget("post_{$post->category->slug}_{$post->slug}");
        }

        // Очищаем кеш похожих постов
        Cache::forget("post_{$post->id}_related");

        // Очищаем кеш категорий, к которым принадлежит пост
        foreach ($post->categories as $category) {
            Cache::forget("category_{$category->slug}");
            Cache::forget("category_{$category->id}_total_views");

            // Очищаем все страницы постов в категории
            for ($i = 1; $i <= 10; $i++) {
                Cache::forget("category_{$category->id}_posts_page_{$i}");
            }
        }

        // Очищаем кеш категорий с количеством постов
        Cache::forget('categories_with_posts_count');
    }
}
