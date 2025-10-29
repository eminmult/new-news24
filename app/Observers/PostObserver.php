<?php

namespace App\Observers;

use App\Http\Controllers\SitemapController;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    /**
     * Handle the Post "saving" event (before any save).
     */
    public function saving(Post $post): void
    {
        // Очищаем кеш при ЛЮБОМ изменении существующего поста
        if ($post->exists && $post->isDirty()) {
            $this->clearPostCaches($post);
        }
    }

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

        // Sitemap обновляется автоматически каждые 10 минут через cron
        // SitemapController::clearCache(); // Убрано - не нужно при использовании cron
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

        // Sitemap обновляется автоматически каждые 10 минут через cron
        // SitemapController::clearCache(); // Убрано - не нужно при использовании cron
        $this->clearPostCaches($post);
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        // Sitemap обновляется автоматически каждые 10 минут через cron
        // SitemapController::clearCache(); // Убрано - не нужно при использовании cron
        $this->clearPostCaches($post);
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        // Sitemap обновляется автоматически каждые 10 минут через cron
        // SitemapController::clearCache(); // Убрано - не нужно при использовании cron
        $this->clearPostCaches($post);
    }

    /**
     * Очистка всех кешей связанных с постом
     */
    protected function clearPostCaches(Post $post): void
    {
        $startTime = microtime(true);

        \Log::info('PostObserver: Начало очистки кеша', [
            'post_id' => $post->id,
            'post_slug' => $post->slug ?? 'N/A',
            'post_title' => $post->title ?? 'N/A',
        ]);

        // Очищаем кеш главной страницы
        Cache::forget('home_slider_posts');
        Cache::forget('home_important_posts');
        Cache::forget('home_main_featured_posts');
        Cache::forget('home_video_posts');
        Cache::forget('home_photo_posts');
        Cache::forget('home_media_posts');

        // Очищаем кеш последних постов (все страницы)
        for ($i = 1; $i <= 20; $i++) {
            Cache::forget("home_latest_posts_page_{$i}");
        }

        // Очищаем кеш поста для всех категорий, к которым он принадлежит
        if ($post->slug) {
            foreach ($post->categories as $category) {
                Cache::forget("post_{$category->slug}_{$post->slug}");
            }
        }

        // Очищаем кеш похожих постов
        Cache::forget("post_{$post->id}_related");

        // Очищаем кеш категорий, к которым принадлежит пост
        foreach ($post->categories as $category) {
            Cache::forget("category_{$category->slug}");
            Cache::forget("category_{$category->id}_total_views");
            Cache::forget("category_{$category->id}_today_posts_count");

            // Очищаем все страницы постов в категории
            for ($i = 1; $i <= 50; $i++) {
                Cache::forget("category_{$category->id}_posts_page_{$i}");
            }
        }

        // Очищаем общие кеши
        Cache::forget('all_categories');
        Cache::forget('menu_categories');
        Cache::forget('categories_with_posts_count');

        // Очищаем Response Cache (Full Page Cache)
        $responseCacheCleared = false;
        if (class_exists('\Spatie\ResponseCache\Facades\ResponseCache')) {
            \Spatie\ResponseCache\Facades\ResponseCache::clear();
            $responseCacheCleared = true;
        }

        // Очищаем nginx fastcgi_cache
        $this->clearNginxCache();

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        \Log::info('PostObserver: Кеш успешно очищен', [
            'post_id' => $post->id,
            'duration_ms' => $duration,
            'response_cache_cleared' => $responseCacheCleared,
        ]);
    }

    /**
     * Очистка nginx fastcgi_cache
     */
    protected function clearNginxCache(): void
    {
        try {
            $startTime = microtime(true);

            // Используем exec вместо shell_exec для синхронного выполнения
            $output = [];
            $returnVar = 0;
            exec('docker exec nginx-new1 find /var/cache/nginx/fastcgi -type f -delete 2>&1', $output, $returnVar);

            $duration = round((microtime(true) - $startTime) * 1000, 2);

            \Log::info('Nginx fastcgi_cache cleared', [
                'duration_ms' => $duration,
                'return_code' => $returnVar,
                'output' => implode("\n", $output)
            ]);
        } catch (\Exception $e) {
            \Log::warning('Failed to clear nginx cache: ' . $e->getMessage());
        }
    }
}
