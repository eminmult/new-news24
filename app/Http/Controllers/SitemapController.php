<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Sitemap Index - главный файл со ссылками на все sitemap
     */
    public function index(): Response
    {
        $lastmod = Cache::remember('sitemap_lastmod', 3600, function () {
            return Post::published()->latest('updated_at')->value('updated_at');
        });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<?xml-stylesheet type="text/xsl" href="' . asset('sitemap-index.xsl') . '"?>';
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Sitemap для новостей (Google News)
        $xml .= '<sitemap>';
        $xml .= '<loc>' . route('sitemap.news') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '</sitemap>';

        // Sitemap для всех постов
        $xml .= '<sitemap>';
        $xml .= '<loc>' . route('sitemap.posts') . '</loc>';
        $xml .= '<lastmod>' . ($lastmod ? $lastmod->toAtomString() : now()->toAtomString()) . '</lastmod>';
        $xml .= '</sitemap>';

        // Sitemap для категорий
        $xml .= '<sitemap>';
        $xml .= '<loc>' . route('sitemap.categories') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '</sitemap>';

        // Sitemap для статических страниц
        $xml .= '<sitemap>';
        $xml .= '<loc>' . route('sitemap.pages') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '</sitemap>';

        $xml .= '</sitemapindex>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Google News Sitemap - последние новости
     * Специальный формат для Google News
     * Показываем новости за последние 3 дня, но если их нет - последние 30 новостей
     */
    public function news(): Response
    {
        $cacheKey = 'sitemap_news';

        $xml = Cache::remember($cacheKey, 300, function () { // кеш на 5 минут
            // Пробуем получить новости за последние 3 дня
            $posts = Post::published()
                ->where('published_at', '>=', now()->subDays(3))
                ->with(['category', 'categories', 'author'])
                ->latest('published_at')
                ->get();

            // Если новостей за 3 дня нет, берем последние 30 новостей
            if ($posts->isEmpty()) {
                $posts = Post::published()
                    ->with(['category', 'categories', 'author'])
                    ->latest('published_at')
                    ->take(30)
                    ->get();
            }

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<?xml-stylesheet type="text/xsl" href="' . asset('sitemap.xsl') . '"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
            $xml .= ' xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"';
            $xml .= ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

            foreach ($posts as $post) {
                // Получаем главную категорию (из many-to-many или из category_id)
                $mainCategory = $post->main_category;

                // Пропускаем посты без категории
                if (!$mainCategory) {
                    continue;
                }

                $xml .= '<url>';
                $xml .= '<loc>' . htmlspecialchars(route('post', ['category' => $mainCategory->slug, 'slug' => $post->slug])) . '</loc>';
                $xml .= '<news:news>';
                $xml .= '<news:publication>';
                $xml .= '<news:name>OLAY.az</news:name>';
                $xml .= '<news:language>az</news:language>';
                $xml .= '</news:publication>';
                $xml .= '<news:publication_date>' . $post->published_at->toAtomString() . '</news:publication_date>';
                $xml .= '<news:title>' . htmlspecialchars($post->title) . '</news:title>';
                $xml .= '<news:keywords>' . htmlspecialchars($mainCategory->name) . '</news:keywords>';
                $xml .= '</news:news>';

                // Добавляем изображение если есть
                if ($post->featured_image) {
                    $xml .= '<image:image>';
                    $xml .= '<image:loc>' . htmlspecialchars($post->featured_image) . '</image:loc>';
                    $xml .= '<image:title>' . htmlspecialchars($post->title) . '</image:title>';
                    $xml .= '</image:image>';
                }

                $xml .= '</url>';
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=300');
    }

    /**
     * Posts Sitemap - все опубликованные посты (разбито на страницы по 5000)
     */
    public function posts(): Response
    {
        $cacheKey = 'sitemap_posts_v3';

        $xml = Cache::remember($cacheKey, 3600, function () {
            // Берем только последние 5000 постов для быстрой генерации
            $posts = Post::published()
                ->with(['category', 'categories'])
                ->orderBy('published_at', 'desc')
                ->limit(5000)
                ->get();

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<?xml-stylesheet type="text/xsl" href="' . asset('sitemap.xsl') . '"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
            $xml .= ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

            foreach ($posts as $post) {
                // Получаем главную категорию (из many-to-many или из category_id)
                $mainCategory = $post->main_category;

                // Пропускаем посты без категории
                if (!$mainCategory) {
                    continue;
                }

                $xml .= '<url>';
                $xml .= '<loc>' . htmlspecialchars(route('post', ['category' => $mainCategory->slug, 'slug' => $post->slug])) . '</loc>';
                $xml .= '<lastmod>' . $post->updated_at->toAtomString() . '</lastmod>';

                // Определяем частоту изменений в зависимости от возраста новости
                $age = $post->published_at->diffInDays(now());
                if ($age < 1) {
                    $changefreq = 'hourly';
                    $priority = '1.0';
                } elseif ($age < 7) {
                    $changefreq = 'daily';
                    $priority = '0.8';
                } elseif ($age < 30) {
                    $changefreq = 'weekly';
                    $priority = '0.6';
                } else {
                    $changefreq = 'monthly';
                    $priority = '0.4';
                }

                $xml .= '<changefreq>' . $changefreq . '</changefreq>';
                $xml .= '<priority>' . $priority . '</priority>';

                // Добавляем изображение
                if ($post->featured_image) {
                    $xml .= '<image:image>';
                    $xml .= '<image:loc>' . htmlspecialchars($post->featured_image) . '</image:loc>';
                    $xml .= '<image:title>' . htmlspecialchars($post->title) . '</image:title>';
                    $xml .= '<image:caption>' . htmlspecialchars($mainCategory->name) . '</image:caption>';
                    $xml .= '</image:image>';
                }

                $xml .= '</url>';
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Categories Sitemap - все категории
     */
    public function categories(): Response
    {
        $cacheKey = 'sitemap_categories';

        $xml = Cache::remember($cacheKey, 3600, function () {
            $categories = Category::where('is_active', true)
                ->withCount(['posts' => function($query) {
                    $query->published();
                }])
                ->orderBy('order')
                ->get();

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<?xml-stylesheet type="text/xsl" href="' . asset('sitemap.xsl') . '"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            foreach ($categories as $category) {
                $xml .= '<url>';
                $xml .= '<loc>' . htmlspecialchars(route('category', $category->slug)) . '</loc>';
                $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
                $xml .= '<changefreq>daily</changefreq>';
                $xml .= '<priority>0.9</priority>';
                $xml .= '</url>';
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Static Pages Sitemap - статические страницы
     */
    public function pages(): Response
    {
        $cacheKey = 'sitemap_pages';

        $xml = Cache::remember($cacheKey, 3600, function () {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<?xml-stylesheet type="text/xsl" href="' . asset('sitemap.xsl') . '"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            // Главная страница
            $xml .= '<url>';
            $xml .= '<loc>' . route('home') . '</loc>';
            $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>hourly</changefreq>';
            $xml .= '<priority>1.0</priority>';
            $xml .= '</url>';

            // О нас
            $xml .= '<url>';
            $xml .= '<loc>' . route('about') . '</loc>';
            $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';

            // Контакты
            $xml .= '<url>';
            $xml .= '<loc>' . route('contact') . '</loc>';
            $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Очистка кеша всех sitemap
     */
    public static function clearCache(): void
    {
        Cache::forget('sitemap_lastmod');
        Cache::forget('sitemap_news');
        Cache::forget('sitemap_posts_v3');
        Cache::forget('sitemap_categories');
        Cache::forget('sitemap_pages');
    }
}
