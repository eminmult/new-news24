<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostType;
use App\Models\PostWidget;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateDlePosts extends Command
{
    protected $signature = 'migrate:dle-posts {--limit=1000 : Количество постов для миграции} {--continue : Продолжить с последнего мигрированного поста}';

    protected $description = 'Мигрировать посты из DLE в Laravel Filament';

    private array $categoryMap = [];
    private ?int $defaultCategoryId = null; // MARAQLI
    private array $stats = [
        'migrated' => 0,
        'skipped' => 0,
        'errors' => 0,
    ];

    public function handle()
    {
        $this->info('========================================');
        $this->info('   Миграция постов из DLE');
        $this->info('========================================');
        $this->newLine();

        try {
            $limit = (int) $this->option('limit');
            $this->info("📝 Лимит: {$limit} постов");
            $this->newLine();

            // Загружаем маппинг категорий
            $this->loadCategoryMap();

            $dle = DB::connection('dle_mysql');

            // Получаем посты из DLE (исключаем категории 33, 28, 32, 21)
            $query = $dle->table('dle_post as p')
                ->leftJoin('dle_post_extras as e', 'p.id', '=', 'e.news_id')
                ->select('p.*', 'e.news_read')
                ->whereRaw("NOT FIND_IN_SET('33', p.category)")
                ->whereRaw("NOT FIND_IN_SET('28', p.category)")
                ->whereRaw("NOT FIND_IN_SET('32', p.category)")
                ->whereRaw("NOT FIND_IN_SET('21', p.category)")
                ->orderBy('p.id', 'asc'); // Начинаем с самых старых

            // Если --continue, продолжаем с последнего мигрированного поста
            if ($this->option('continue')) {
                $lastId = cache('dle_migration_last_id', 0);
                if ($lastId > 0) {
                    $this->info("📍 Продолжаем с поста ID: {$lastId}");
                    $query = $query->where('p.id', '>', $lastId);
                }
            }

            $dlePosts = $query->limit($limit)->get();

            $this->info("📰 Найдено постов в DLE: {$dlePosts->count()}");
            $this->newLine();

            $bar = $this->output->createProgressBar($dlePosts->count());
            $bar->start();

            foreach ($dlePosts as $dlePost) {
                try {
                    $this->migratePost($dlePost);
                    $this->stats['migrated']++;

                    // Сохраняем ID последнего мигрированного поста
                    cache(['dle_migration_last_id' => $dlePost->id], now()->addDays(30));
                } catch (\Exception $e) {
                    $this->stats['errors']++;
                    $this->newLine();
                    $this->error("Ошибка при миграции поста ID {$dlePost->id}: " . $e->getMessage());
                }
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);
            $this->info("✓ Успешно мигрировано постов: {$this->stats['migrated']}");

            if ($this->stats['skipped'] > 0) {
                $this->warn("⚠ Пропущено: {$this->stats['skipped']}");
            }

            if ($this->stats['errors'] > 0) {
                $this->error("❌ Ошибок: {$this->stats['errors']}");
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Ошибка миграции: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    protected function loadCategoryMap()
    {
        $dle = DB::connection('dle_mysql');

        // DLE категории
        $dleCategories = $dle->table('dle_category')->get()->keyBy('id');

        // Laravel категории
        $laravelCategories = Category::all()->keyBy('slug');

        // Создаем маппинг: DLE ID → Laravel ID
        foreach ($dleCategories as $dleId => $dleCat) {
            if (isset($laravelCategories[$dleCat->alt_name])) {
                $this->categoryMap[$dleId] = $laravelCategories[$dleCat->alt_name]->id;
            }
        }

        // Находим дефолтную категорию MARAQLI
        if (isset($laravelCategories['maraqli'])) {
            $this->defaultCategoryId = $laravelCategories['maraqli']->id;
            $this->info("📁 Дефолтная категория (MARAQLI): ID {$this->defaultCategoryId}");
        }

        $this->info("📁 Загружен маппинг категорий: " . count($this->categoryMap));
    }

    protected function migratePost($dlePost)
    {
        // Проверяем, есть ли уже такой пост
        if (Post::where('slug', $dlePost->alt_name)->exists()) {
            $this->stats['skipped']++;
            return;
        }

        // Парсим xfields
        $xfields = $this->parseXfields($dlePost->xfields);

        // Формируем заголовок
        $title = $dlePost->title;
        if (!empty($xfields['video-foto-yenilenib'])) {
            $title = $title . ' - ' . $xfields['video-foto-yenilenib'];
        }

        // Очищаем заголовок
        $title = $this->cleanText($title);

        // Обрабатываем категории (если нет - будет добавлена MARAQLI)
        $categoryIds = $this->mapCategories($dlePost->category);

        // Создаем пост
        $post = Post::create([
            'title' => $title,
            'slug' => $this->generateUniqueSlug($dlePost->alt_name),
            'old_url' => $dlePost->alt_name, // Сохраняем старый URL для редиректов
            'content' => $this->cleanText($dlePost->short_story),
            'meta_title' => $this->cleanText($dlePost->metatitle ?: ''),
            'meta_description' => $this->cleanText($dlePost->descr ?: ''),
            'meta_keywords' => $this->cleanText($dlePost->keywords ?: ''),
            'views' => $dlePost->news_read ?? 0,
            'is_published' => true,
            'published_at' => $dlePost->date,
            'show_on_homepage' => true,
            'show_in_slider' => false,
            'show_in_types_block' => false,
            'show_in_important_today' => false,
            'is_hidden' => false,
        ]);

        // Привязываем категории
        $post->categories()->attach($categoryIds);

        // Обрабатываем изображения
        $this->processImages($post, $xfields);

        // Создаем виджеты
        $this->createWidgets($post, $xfields);

        // Назначаем типы постов
        $this->assignPostTypes($post, $xfields);
    }

    protected function parseXfields(string $xfields): array
    {
        if (empty($xfields)) {
            return [];
        }

        $result = [];
        $parts = explode('||', $xfields);

        foreach ($parts as $part) {
            if (strpos($part, '|') !== false) {
                $fieldParts = explode('|', $part, 2);
                $fieldName = trim($fieldParts[0]);
                $fieldValue = $fieldParts[1] ?? '';

                if (!empty($fieldName)) {
                    $result[$fieldName] = $fieldValue;
                }
            }
        }

        return $result;
    }

    protected function mapCategories(string $categoryString): array
    {
        $dleIds = array_filter(array_map('trim', explode(',', $categoryString)));
        $laravelIds = [];

        foreach ($dleIds as $dleId) {
            if (isset($this->categoryMap[$dleId])) {
                $laravelIds[] = $this->categoryMap[$dleId];
            }
        }

        // Если категорий не найдено, используем дефолтную (MARAQLI)
        if (empty($laravelIds) && $this->defaultCategoryId) {
            $laravelIds[] = $this->defaultCategoryId;
        }

        return array_unique($laravelIds);
    }

    protected function generateUniqueSlug(string $slug): string
    {
        $originalSlug = $slug;
        $counter = 1;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function processImages(Post $post, array $xfields)
    {
        $images = [];

        // Приоритет: image-logo > image
        if (!empty($xfields['image-logo'])) {
            $imageUrl = $this->parseImageField($xfields['image-logo']);
            if ($imageUrl) {
                $images[] = $imageUrl;
            }
        } elseif (!empty($xfields['image'])) {
            $imageUrl = $this->parseImageField($xfields['image']);
            if ($imageUrl) {
                $images[] = $imageUrl;
            }
        }

        // Галереи
        if (!empty($xfields['gallery'])) {
            $galleryImages = $this->parseGalleryField($xfields['gallery']);
            $images = array_merge($images, $galleryImages);
        }

        if (!empty($xfields['gallery-logo'])) {
            $galleryImages = $this->parseGalleryField($xfields['gallery-logo']);
            $images = array_merge($images, $galleryImages);
        }

        // Загружаем изображения в Spatie Media Library
        // При добавлении автоматически генерируются все конверсии (thumb, medium, large)
        // определенные в методе registerMediaConversions модели Post
        foreach ($images as $imageUrl) {
            try {
                $media = $post->addMediaFromUrl($imageUrl)
                    ->toMediaCollection('post-gallery');

                // Конверсии генерируются автоматически:
                // - thumb: 450x300px (качество 78%)
                // - medium: 700x467px (качество 80%)
                // - large: 1200x800px (качество 85%)
                // - webp: макс 1000px (качество 82%)
            } catch (\Exception $e) {
                // Пропускаем изображения, которые не удалось загрузить
                $this->warn("Не удалось загрузить изображение: {$imageUrl}");
            }
        }
    }

    protected function parseImageField(string $imageData): ?string
    {
        // Формат: 2024-04/файл.webp&#124;1&#124;0&#124;850x530&#124;22.Kb
        $parts = explode('&#124;', $imageData);
        if (empty($parts[0])) {
            return null;
        }

        $imagePath = $parts[0];
        return 'http://178.63.72.226:8083/uploads/posts/' . $imagePath;
    }

    protected function parseGalleryField(string $galleryData): array
    {
        $images = [];

        // Проверяем формат с &#124; (новый формат)
        if (strpos($galleryData, '&#124;') !== false) {
            $items = explode('&#124;&#124;', $galleryData);
            foreach ($items as $item) {
                $url = $this->parseImageField($item);
                if ($url) {
                    $images[] = $url;
                }
            }
        } else {
            // Старый формат: через запятую
            $items = explode(',', $galleryData);
            foreach ($items as $item) {
                $item = trim($item);
                if (!empty($item)) {
                    $images[] = 'http://178.63.72.226:8083/uploads/posts/' . $item;
                }
            }
        }

        return $images;
    }

    protected function createWidgets(Post $post, array $xfields)
    {
        $order = 1;

        // YouTube
        if (!empty($xfields['youtube'])) {
            PostWidget::create([
                'post_id' => $post->id,
                'type' => 'youtube',
                'content' => $xfields['youtube'],
                'order' => $order++,
            ]);
        }

        if (!empty($xfields['youtube2'])) {
            PostWidget::create([
                'post_id' => $post->id,
                'type' => 'youtube',
                'content' => $xfields['youtube2'],
                'order' => $order++,
            ]);
        }

        // Instagram
        foreach (['instagram', 'instagram2', 'instagram3', 'instagram4', 'instagram5'] as $field) {
            if (!empty($xfields[$field])) {
                PostWidget::create([
                    'post_id' => $post->id,
                    'type' => 'instagram',
                    'content' => $xfields[$field],
                    'order' => $order++,
                ]);
            }
        }

        // Telegram
        if (!empty($xfields['telegram-video'])) {
            PostWidget::create([
                'post_id' => $post->id,
                'type' => 'telegram',
                'content' => $xfields['telegram-video'],
                'order' => $order++,
            ]);
        }

        // Facebook
        if (!empty($xfields['fbvideo'])) {
            PostWidget::create([
                'post_id' => $post->id,
                'type' => 'fbvideo',
                'content' => $xfields['fbvideo'],
                'order' => $order++,
            ]);
        }

        // OK.ru
        if (!empty($xfields['odna'])) {
            PostWidget::create([
                'post_id' => $post->id,
                'type' => 'okru',
                'content' => $xfields['odna'],
                'order' => $order++,
            ]);
        }

        // Twitter/X
        if (!empty($xfields['twitter'])) {
            PostWidget::create([
                'post_id' => $post->id,
                'type' => 'x',
                'content' => $xfields['twitter'],
                'order' => $order++,
            ]);
        }

        // HTML
        if (!empty($xfields['html'])) {
            PostWidget::create([
                'post_id' => $post->id,
                'type' => 'html',
                'content' => $xfields['html'],
                'order' => $order++,
            ]);
        }
    }

    protected function assignPostTypes(Post $post, array $xfields)
    {
        $typeIds = [];

        // Тип "Фото" (id: 2)
        if (!empty($xfields['gallery']) || !empty($xfields['gallery-logo'])) {
            $typeIds[] = 2;
        }

        // Тип "Видео" (id: 3)
        $videoFields = ['youtube', 'youtube2', 'telegram-video', 'instagram', 'instagram2',
                        'instagram3', 'instagram4', 'instagram5', 'fbvideo', 'odna'];

        foreach ($videoFields as $field) {
            if (!empty($xfields[$field])) {
                $typeIds[] = 3;
                break;
            }
        }

        if (!empty($typeIds)) {
            $post->types()->attach(array_unique($typeIds));
        }
    }

    protected function cleanText(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // Убираем экранированные кавычки
        $text = str_replace(['\\"', "\\'", '\"', "\'"], ['"', "'", '"', "'"], $text);

        // Декодируем HTML entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Убираем двойные тире (с любым количеством пробелов)
        $text = preg_replace('/\s+-\s+-\s+/', ' - ', $text);

        // Убираем лишние пробелы
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }
}
