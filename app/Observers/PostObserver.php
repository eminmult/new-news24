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
        // Debug log
        file_put_contents(storage_path('logs/observer-debug.log'), date('Y-m-d H:i:s') . " - POST CREATED: {$post->id} - {$post->title}\n", FILE_APPEND);

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
        // Debug log
        file_put_contents(storage_path('logs/observer-debug.log'), date('Y-m-d H:i:s') . " - POST UPDATED: {$post->id} - {$post->title}\n", FILE_APPEND);

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
        // Debug log для удаления
        file_put_contents(storage_path('logs/observer-debug.log'), date('Y-m-d H:i:s') . " - POST DELETED: {$post->id} - {$post->title}\n", FILE_APPEND);

        // Явно загружаем связи, даже если пост удален
        $post->loadMissing('categories');

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
            'is_deleted' => $post->trashed(),
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
        $categoriesCleared = 0;
        if ($post->slug && $post->categories) {
            foreach ($post->categories as $category) {
                $cacheKey = "post_{$category->slug}_{$post->slug}";
                Cache::forget($cacheKey);
                $categoriesCleared++;
                \Log::debug("PostObserver: Очищен кеш поста", ['cache_key' => $cacheKey]);
            }
        }

        // Очищаем кеш похожих постов
        Cache::forget("post_{$post->id}_related");

        // Очищаем кеш категорий, к которым принадлежит пост
        if ($post->categories) {
            foreach ($post->categories as $category) {
                Cache::forget("category_{$category->slug}");
                Cache::forget("category_{$category->id}_total_views");
                Cache::forget("category_{$category->id}_today_posts_count");

                // Очищаем все страницы постов в категории
                for ($i = 1; $i <= 50; $i++) {
                    Cache::forget("category_{$category->id}_posts_page_{$i}");
                }
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
            'categories_cleared' => $categoriesCleared,
            'duration_ms' => $duration,
            'response_cache_cleared' => $responseCacheCleared,
        ]);
    }

    /**
     * Очистка nginx fastcgi_cache через триггер-файл
     */
    protected function clearNginxCache(): void
    {
        try {
            // Создаем триггер-файл для очистки nginx cache
            // Скрипт на хосте будет проверять этот файл и очищать cache
            $triggerFile = storage_path('framework/cache/nginx_clear_trigger');
            touch($triggerFile);
            file_put_contents($triggerFile, date('Y-m-d H:i:s'));

            // Также вызываем скрипт напрямую, если доступен
            $scriptPath = '/home/admin/clear-nginx-cache.sh';
            if (file_exists($scriptPath)) {
                exec($scriptPath . ' > /dev/null 2>&1 &');
            }

            \Log::info('Nginx cache clear triggered', [
                'trigger_file' => $triggerFile,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            \Log::warning('Failed to trigger nginx cache clear: ' . $e->getMessage());
        }
    }
}
