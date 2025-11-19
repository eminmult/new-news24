<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class SitemapController extends Controller
{
    /**
     * Sitemap Index - главный файл со ссылками на все sitemap
     * ЧИТАЕТ ИЗ REDIS (без запросов к БД)
     */
    public function index(): Response
    {
        $xml = Redis::get('sitemap:index');

        // Если в Redis нет - генерируем (fallback)
        if (!$xml) {
            $xml = $this->generateIndexXml();
            Redis::setex('sitemap:index', 900, $xml);
        }

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=900');
    }

    /**
     * ПУБЛИЧНЫЙ метод для генерации index XML (используется командой)
     */
    public function generateIndexXml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Sitemap для новостей (Google News)
        $xml .= '<sitemap>';
        $xml .= '<loc>' . route('sitemap.news') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '</sitemap>';

        // Динамические sitemap для постов по периодам
        $periods = $this->getPostsPeriods();

        foreach ($periods as $period) {
            $xml .= '<sitemap>';
            if (isset($period['month'])) {
                $xml .= '<loc>' . route('sitemap.posts.month', ['year' => $period['year'], 'month' => $period['month']]) . '</loc>';
            } else {
                $xml .= '<loc>' . route('sitemap.posts.year', ['year' => $period['year']]) . '</loc>';
            }
            $xml .= '<lastmod>' . $period['lastmod']->toAtomString() . '</lastmod>';
            $xml .= '</sitemap>';
        }

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

        // Sitemap для авторов (E-A-T)
        $xml .= '<sitemap>';
        $xml .= '<loc>' . route('sitemap.authors') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '</sitemap>';

        // Sitemap для изображений
        $xml .= '<sitemap>';
        $xml .= '<loc>' . route('sitemap.images') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '</sitemap>';

        $xml .= '</sitemapindex>';

        return $xml;
    }

    /**
     * Получить список периодов для генерации sitemap
     * ОПТИМИЗИРОВАНО: один запрос вместо множества
     */
    private function getPostsPeriods(): array
    {
        // ОДИН запрос для получения всех периодов с lastmod
        $periodsData = \DB::table('posts')
            ->selectRaw('
                YEAR(published_at) as year,
                MONTH(published_at) as month,
                MAX(updated_at) as lastmod,
                COUNT(*) as count
            ')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('is_published', true)
            ->whereNull('deleted_at')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        $periods = [];
        $currentYear = now()->year;

        foreach ($periodsData as $data) {
            // Для 2021-2025: по месяцам (большие годы тоже разбиваем)
            if ($data->year >= 2021) {
                $periods[] = [
                    'year' => $data->year,
                    'month' => str_pad($data->month, 2, '0', STR_PAD_LEFT),
                    'lastmod' => new \Carbon\Carbon($data->lastmod),
                ];
            }
            // Для 2018-2020: группируем по годам (небольшие объемы)
            else if ($data->year >= 2018) {
                // Проверяем, есть ли уже этот год
                $yearExists = false;
                foreach ($periods as &$period) {
                    if (!isset($period['month']) && $period['year'] == $data->year) {
                        // Обновляем lastmod если этот месяц новее
                        if (new \Carbon\Carbon($data->lastmod) > $period['lastmod']) {
                            $period['lastmod'] = new \Carbon\Carbon($data->lastmod);
                        }
                        $yearExists = true;
                        break;
                    }
                }

                if (!$yearExists) {
                    $periods[] = [
                        'year' => $data->year,
                        'lastmod' => new \Carbon\Carbon($data->lastmod),
                    ];
                }
            }
        }

        return $periods;
    }

    /**
     * Google News Sitemap - ЧИТАЕТ ИЗ REDIS
     */
    public function news(): Response
    {
        $xml = Redis::get('sitemap:news');

        if (!$xml) {
            $xml = $this->generateNewsXml();
            Redis::setex('sitemap:news', 300, $xml);
        }

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=300');
    }

    /**
     * ПУБЛИЧНЫЙ метод генерации News XML
     */
    public function generateNewsXml(): string
    {
        // Пробуем получить новости за последние 2 дня (рекомендация Google News)
            $posts = Post::published()
                ->where('published_at', '>=', now()->subDays(2))
                ->with(['category', 'categories', 'author'])
                ->latest('published_at')
                ->get();

            // Если новостей за 2 дня мало, берем до 1000 последних новостей
            if ($posts->count() < 100) {
                $posts = Post::published()
                    ->with(['category', 'categories', 'author'])
                    ->latest('published_at')
                    ->take(1000)
                    ->get();
            }

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
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
                $xml .= '<news:name>News24.az</news:name>';
                $xml .= '<news:language>az</news:language>';
                $xml .= '</news:publication>';
                $xml .= '<news:publication_date>' . $post->published_at->toAtomString() . '</news:publication_date>';
                $xml .= '<news:title>' . htmlspecialchars($post->title) . '</news:title>';
                
                // Enhanced keywords: category + tags
                $keywords = [$mainCategory->name];
                if ($post->tags && $post->tags->count() > 0) {
                    $keywords = array_merge($keywords, $post->tags->pluck('name')->take(3)->toArray());
                }
                $xml .= '<news:keywords>' . htmlspecialchars(implode(', ', $keywords)) . '</news:keywords>';
                
                // Geo locations if available
                if ($post->meta_keywords) {
                    $xml .= '<news:geo_locations>' . htmlspecialchars($post->meta_keywords) . '</news:geo_locations>';
                }
                
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
    }

    /**
     * Posts Sitemap по месяцам (для 2021-2025) - ЧИТАЕТ ИЗ REDIS
     */
    public function postsByMonth(int $year, string $month): Response
    {
        $xml = Redis::get("sitemap:posts:{$year}-{$month}");

        // Если в Redis нет - генерируем (fallback)
        if (!$xml) {
            $xml = $this->generatePostsXmlDirect($year, $month);
            $isCurrentMonth = ($year == now()->year && $month == now()->format('m'));
            $ttl = $isCurrentMonth ? 300 : 43200;
            Redis::setex("sitemap:posts:{$year}-{$month}", $ttl, $xml);
        }

        $isCurrentMonth = ($year == now()->year && $month == now()->format('m'));
        $cacheDuration = $isCurrentMonth ? 300 : 43200;

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', "public, max-age={$cacheDuration}");
    }

    /**
     * Posts Sitemap по годам (для 2018-2020) - ЧИТАЕТ ИЗ REDIS
     */
    public function postsByYear(int $year): Response
    {
        $xml = Redis::get("sitemap:posts:{$year}");

        // Если в Redis нет - генерируем (fallback)
        if (!$xml) {
            $xml = $this->generatePostsXmlDirect($year);
            Redis::setex("sitemap:posts:{$year}", 86400, $xml);
        }

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * ПУБЛИЧНЫЙ метод генерации XML для постов (используется командой)
     * Использует прямой SQL запрос для максимальной скорости
     */
    public function generatePostsXmlDirect(int $year, ?string $month = null): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
        $xml .= ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        // ОДИН SQL запрос с подзапросом для первой категории
        $sql = "
            SELECT
                p.slug,
                p.title,
                p.updated_at,
                p.published_at,
                p.featured_image,
                c.slug as category_slug,
                c.name as category_name
            FROM posts p
            INNER JOIN (
                SELECT post_id, MIN(category_id) as category_id
                FROM category_post
                GROUP BY post_id
            ) cp ON p.id = cp.post_id
            INNER JOIN categories c ON cp.category_id = c.id
            WHERE p.published_at IS NOT NULL
                AND p.published_at <= NOW()
                AND p.is_published = 1
                AND p.deleted_at IS NULL
                AND YEAR(p.published_at) = ?
        ";

        $params = [$year];

        if ($month) {
            $sql .= " AND MONTH(p.published_at) = ?";
            $params[] = (int)$month;
        }

        $sql .= " ORDER BY p.published_at DESC";

        $posts = \DB::select($sql, $params);

        $nowTimestamp = time();
        $baseUrl = config('app.url');

        foreach ($posts as $post) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($baseUrl . '/' . $post->category_slug . '/' . $post->slug) . '</loc>';
            $xml .= '<lastmod>' . date('c', strtotime($post->updated_at)) . '</lastmod>';

            // Приоритеты по возрасту (быстрее без Carbon)
            $publishedTimestamp = strtotime($post->published_at);
            $age = floor(($nowTimestamp - $publishedTimestamp) / 86400);

            if ($age < 1) {
                $changefreq = 'hourly';
                $priority = '0.9';
            } elseif ($age < 7) {
                $changefreq = 'daily';
                $priority = '0.7';
            } elseif ($age < 30) {
                $changefreq = 'weekly';
                $priority = '0.5';
            } else {
                $changefreq = 'monthly';
                $priority = '0.3';
            }

            $xml .= '<changefreq>' . $changefreq . '</changefreq>';
            $xml .= '<priority>' . $priority . '</priority>';

            // Изображение
            if ($post->featured_image) {
                $xml .= '<image:image>';
                $xml .= '<image:loc>' . htmlspecialchars($post->featured_image) . '</image:loc>';
                $xml .= '<image:title>' . htmlspecialchars($post->title) . '</image:title>';
                $xml .= '<image:caption>' . htmlspecialchars($post->category_name) . '</image:caption>';
                $xml .= '</image:image>';
            }

            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Генерация XML для постов с правильными приоритетами
     */
    private function generatePostsXml($posts): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
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

            // ИСПРАВЛЕННЫЕ приоритеты: 1.0 только для главной страницы!
            $age = $post->published_at->diffInDays(now());
            if ($age < 1) {
                $changefreq = 'hourly';
                $priority = '0.9';  // Было 1.0 - исправлено
            } elseif ($age < 7) {
                $changefreq = 'daily';
                $priority = '0.7';  // Было 0.8 - уменьшено
            } elseif ($age < 30) {
                $changefreq = 'weekly';
                $priority = '0.5';  // Было 0.6 - уменьшено
            } else {
                $changefreq = 'monthly';
                $priority = '0.3';  // Было 0.4 - уменьшено
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
    }

    /**
     * Categories Sitemap - ЧИТАЕТ ИЗ REDIS
     */
    public function categories(): Response
    {
        $xml = Redis::get('sitemap:categories');

        if (!$xml) {
            $xml = $this->generateCategoriesXml();
            Redis::setex('sitemap:categories', 900, $xml);
        }

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=900');
    }

    /**
     * ПУБЛИЧНЫЙ метод генерации Categories XML
     */
    public function generateCategoriesXml(): string
    {
        $categories = Category::where('is_active', true)
            ->withCount(['posts' => function($query) {
                $query->published();
            }])
            ->orderBy('order')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($categories as $category) {
            // Используем реальную дату последнего поста в категории
            $lastPost = $category->posts()->published()->latest('updated_at')->first();
            $lastmod = $lastPost ? $lastPost->updated_at : $category->updated_at;

            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('category', $category->slug)) . '</loc>';
            $xml .= '<lastmod>' . $lastmod->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>hourly</changefreq>';
            $xml .= '<priority>0.9</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Static Pages Sitemap - ЧИТАЕТ ИЗ REDIS
     */
    public function pages(): Response
    {
        $xml = Redis::get('sitemap:pages');

        if (!$xml) {
            $xml = $this->generatePagesXml();
            Redis::setex('sitemap:pages', 86400, $xml);
        }

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * ПУБЛИЧНЫЙ метод генерации Pages XML
     */
    public function generatePagesXml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
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

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Authors Sitemap - ЧИТАЕТ ИЗ REDIS
     */
    public function authors(): Response
    {
        $xml = Redis::get('sitemap:authors');

        if (!$xml) {
            $xml = $this->generateAuthorsXml();
            Redis::setex('sitemap:authors', 3600, $xml);
        }

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * ПУБЛИЧНЫЙ метод генерации Authors XML
     */
    public function generateAuthorsXml(): string
    {
        $authors = \App\Models\User::where('is_active', true)
            ->whereIn('role', [\App\Models\User::ROLE_AUTHOR, \App\Models\User::ROLE_EDITOR, \App\Models\User::ROLE_ADMIN])
            ->whereNotNull('slug')
            ->withCount(['posts' => function($query) {
                $query->published();
            }])
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($authors as $author) {
            $lastPost = $author->posts()->published()->latest('updated_at')->first();
            $lastmod = $lastPost ? $lastPost->updated_at : $author->updated_at;

            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('author.show', $author->slug)) . '</loc>';
            $xml .= '<lastmod>' . $lastmod->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Images Sitemap Index - индекс с разделением по месяцам
     * ЧИТАЕТ ИЗ REDIS
     */
    public function imagesIndex(): Response
    {
        $xml = Redis::get('sitemap:images:index');

        if (!$xml) {
            $xml = $this->generateImagesIndexXml();
            Redis::setex('sitemap:images:index', 900, $xml);
        }

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=900');
    }

    /**
     * ПУБЛИЧНЫЙ метод генерации Images Index XML
     */
    public function generateImagesIndexXml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Получаем периоды с постами (используем тот же метод)
        $periods = $this->getPostsPeriods();

        foreach ($periods as $period) {
            $xml .= '<sitemap>';
            if (isset($period['month'])) {
                $xml .= '<loc>' . route('sitemap.images.month', ['year' => $period['year'], 'month' => $period['month']]) . '</loc>';
            } else {
                $xml .= '<loc>' . route('sitemap.images.year', ['year' => $period['year']]) . '</loc>';
            }
            $xml .= '<lastmod>' . $period['lastmod']->toAtomString() . '</lastmod>';
            $xml .= '</sitemap>';
        }

        $xml .= '</sitemapindex>';

        return $xml;
    }

    /**
     * Images Sitemap по месяцам - ЧИТАЕТ ИЗ REDIS
     */
    public function imagesByMonth(int $year, string $month): Response
    {
        $xml = Redis::get("sitemap:images:{$year}-{$month}");

        if (!$xml) {
            $xml = $this->generateImagesXmlDirect($year, $month);
            $isCurrentMonth = ($year == now()->year && $month == now()->format('m'));
            $ttl = $isCurrentMonth ? 300 : 43200;
            Redis::setex("sitemap:images:{$year}-{$month}", $ttl, $xml);
        }

        $isCurrentMonth = ($year == now()->year && $month == now()->format('m'));
        $cacheDuration = $isCurrentMonth ? 300 : 43200;

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', "public, max-age={$cacheDuration}");
    }

    /**
     * Images Sitemap по годам - ЧИТАЕТ ИЗ REDIS
     */
    public function imagesByYear(int $year): Response
    {
        $xml = Redis::get("sitemap:images:{$year}");

        if (!$xml) {
            $xml = $this->generateImagesXmlDirect($year);
            Redis::setex("sitemap:images:{$year}", 86400, $xml);
        }

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * ПУБЛИЧНЫЙ метод генерации Images XML
     * Только изображения, без текста статей
     */
    public function generateImagesXmlDirect(int $year, ?string $month = null): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
        $xml .= ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"';
        $xml .= ' xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        // SQL запрос для получения постов с изображениями из таблицы media
        $sql = "
            SELECT
                p.slug,
                p.title,
                p.updated_at,
                p.published_at,
                c.slug as category_slug,
                m.id as media_id,
                m.file_name,
                m.disk
            FROM posts p
            INNER JOIN (
                SELECT post_id, MIN(category_id) as category_id
                FROM category_post
                GROUP BY post_id
            ) cp ON p.id = cp.post_id
            INNER JOIN categories c ON cp.category_id = c.id
            INNER JOIN (
                SELECT model_id, MIN(id) as min_id
                FROM media
                WHERE model_type = ?
                    AND collection_name = 'post-gallery'
                GROUP BY model_id
            ) m_first ON p.id = m_first.model_id
            INNER JOIN media m ON m_first.min_id = m.id
            WHERE p.published_at IS NOT NULL
                AND p.published_at <= NOW()
                AND p.is_published = 1
                AND p.deleted_at IS NULL
                AND YEAR(p.published_at) = ?
        ";

        $params = ['App\Models\Post', $year];

        if ($month) {
            $sql .= " AND MONTH(p.published_at) = ?";
            $params[] = (int)$month;
        }

        $sql .= " ORDER BY p.published_at DESC";

        $posts = \DB::select($sql, $params);

        $nowTimestamp = time();
        $baseUrl = config('app.url');
        $storageUrl = config('app.url') . '/storage';

        foreach ($posts as $post) {
            // Формируем URL webp версии изображения
            $fileNameWithoutExt = pathinfo($post->file_name, PATHINFO_FILENAME);
            $imageUrl = $storageUrl . '/' . $post->media_id . '/conversions/' . $fileNameWithoutExt . '-webp.webp';

            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($baseUrl . '/' . $post->category_slug . '/' . $post->slug) . '</loc>';
            $xml .= '<lastmod>' . date('c', strtotime($post->updated_at)) . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.6</priority>';

            // Изображение
            $xml .= '<image:image>';
            $xml .= '<image:loc>' . htmlspecialchars($imageUrl) . '</image:loc>';
            $xml .= '<image:title>' . htmlspecialchars($post->title) . '</image:title>';
            $xml .= '</image:image>';

            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Очистка кеша всех sitemap (Redis)
     */
    public static function clearCache(): void
    {
        // Очищаем основные sitemap
        Redis::del('sitemap:index');
        Redis::del('sitemap:news');
        Redis::del('sitemap:categories');
        Redis::del('sitemap:pages');
        Redis::del('sitemap:authors');
        Redis::del('sitemap:images:index');

        // Очищаем все sitemap постов по периодам (2021-2025 по месяцам)
        $currentYear = now()->year;
        for ($year = $currentYear; $year >= 2021; $year--) {
            for ($month = 1; $month <= 12; $month++) {
                $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
                Redis::del("sitemap:posts:{$year}-{$monthStr}");
                Redis::del("sitemap:images:{$year}-{$monthStr}");
            }
        }

        // Очищаем sitemap постов по годам (2018-2020)
        for ($year = 2020; $year >= 2018; $year--) {
            Redis::del("sitemap:posts:{$year}");
            Redis::del("sitemap:images:{$year}");
        }
    }
}
