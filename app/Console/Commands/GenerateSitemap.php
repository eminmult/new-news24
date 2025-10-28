<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\SitemapController;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--type=all : all, index, posts, categories, pages, news, images}';
    protected $description = 'Генерация sitemap в Redis (без запросов к БД при загрузке)';

    public function handle()
    {
        $type = $this->option('type');

        $this->info('🚀 Начинаю генерацию sitemap в Redis...');

        $startTime = microtime(true);

        switch ($type) {
            case 'index':
                $this->generateIndex();
                break;
            case 'posts':
                $this->generateAllPosts();
                break;
            case 'categories':
                $this->generateCategories();
                break;
            case 'pages':
                $this->generatePages();
                break;
            case 'news':
                $this->generateNews();
                break;
            case 'images':
                $this->generateAllImages();
                break;
            case 'all':
            default:
                $this->generateNews();
                $this->generateAllPosts();
                $this->generateAllImages();
                $this->generateCategories();
                $this->generatePages();
                $this->generateIndex();
                break;
        }

        $duration = round(microtime(true) - $startTime, 2);
        $this->info("✅ Готово за {$duration} сек");

        return 0;
    }

    private function generateIndex()
    {
        $this->info('📑 Генерирую index sitemap...');
        $xml = app(SitemapController::class)->generateIndexXml();
        Redis::setex('sitemap:index', 900, $xml); // 15 минут
        $this->line('   ✓ sitemap.xml');
    }

    private function generateAllPosts()
    {
        $this->info('📝 Генерирую posts sitemap...');

        // Получаем все периоды
        $periodsData = \DB::table('posts')
            ->selectRaw('YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as count')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('is_published', true)
            ->whereNull('deleted_at')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        $bar = $this->output->createProgressBar($periodsData->count());
        $bar->start();

        foreach ($periodsData as $data) {
            // 2021-2025: по месяцам
            if ($data->year >= 2021) {
                $month = str_pad($data->month, 2, '0', STR_PAD_LEFT);
                $xml = app(SitemapController::class)->generatePostsXmlDirect($data->year, $month);

                $isCurrentMonth = ($data->year == now()->year && $data->month == now()->month);
                $ttl = $isCurrentMonth ? 300 : 43200; // 5 минут или 12 часов

                Redis::setex("sitemap:posts:{$data->year}-{$month}", $ttl, $xml);
            }
            // 2018-2020: по годам
            else if ($data->year >= 2018) {
                $key = "sitemap:posts:{$data->year}";
                // Генерируем только если еще не сгенерировано для этого года
                if (!Redis::exists($key)) {
                    $xml = app(SitemapController::class)->generatePostsXmlDirect($data->year);
                    Redis::setex($key, 86400, $xml); // 24 часа
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function generateCategories()
    {
        $this->info('📂 Генерирую categories sitemap...');
        $xml = app(SitemapController::class)->generateCategoriesXml();
        Redis::setex('sitemap:categories', 900, $xml); // 15 минут
        $this->line('   ✓ sitemap-categories.xml');
    }

    private function generatePages()
    {
        $this->info('📄 Генерирую pages sitemap...');
        $xml = app(SitemapController::class)->generatePagesXml();
        Redis::setex('sitemap:pages', 86400, $xml); // 24 часа
        $this->line('   ✓ sitemap-pages.xml');
    }

    private function generateNews()
    {
        $this->info('📰 Генерирую news sitemap...');
        $xml = app(SitemapController::class)->generateNewsXml();
        Redis::setex('sitemap:news', 300, $xml); // 5 минут
        $this->line('   ✓ sitemap-news.xml');
    }

    private function generateAllImages()
    {
        $this->info('🖼️  Генерирую images sitemap...');

        // Получаем все периоды с изображениями из таблицы media
        $periodsData = \DB::table('posts')
            ->join('media', function($join) {
                $join->on('posts.id', '=', 'media.model_id')
                     ->where('media.model_type', '=', 'App\\Models\\Post')
                     ->where('media.collection_name', '=', 'post-gallery');
            })
            ->selectRaw('YEAR(posts.published_at) as year, MONTH(posts.published_at) as month, COUNT(DISTINCT posts.id) as count')
            ->whereNotNull('posts.published_at')
            ->where('posts.published_at', '<=', now())
            ->where('posts.is_published', true)
            ->whereNull('posts.deleted_at')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        $bar = $this->output->createProgressBar($periodsData->count());
        $bar->start();

        foreach ($periodsData as $data) {
            // 2021-2025: по месяцам
            if ($data->year >= 2021) {
                $month = str_pad($data->month, 2, '0', STR_PAD_LEFT);
                $xml = app(SitemapController::class)->generateImagesXmlDirect($data->year, $month);

                $isCurrentMonth = ($data->year == now()->year && $data->month == now()->month);
                $ttl = $isCurrentMonth ? 300 : 43200; // 5 минут или 12 часов

                Redis::setex("sitemap:images:{$data->year}-{$month}", $ttl, $xml);
            }
            // 2018-2020: по годам
            else if ($data->year >= 2018) {
                $key = "sitemap:images:{$data->year}";
                // Генерируем только если еще не сгенерировано для этого года
                if (!Redis::exists($key)) {
                    $xml = app(SitemapController::class)->generateImagesXmlDirect($data->year);
                    Redis::setex($key, 86400, $xml); // 24 часа
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // Генерируем индекс images sitemap
        $xml = app(SitemapController::class)->generateImagesIndexXml();
        Redis::setex('sitemap:images:index', 900, $xml); // 15 минут
        $this->line('   ✓ sitemap-images.xml');
    }
}
