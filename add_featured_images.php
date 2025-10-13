<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Получаем все посты без featured_media_id
$posts = Post::whereNull('featured_media_id')->get();

echo "Найдено {$posts->count()} постов без главного изображения\n\n";

// Список тестовых изображений с тематикой
$images = [
    'news' => [
        ['name' => 'Президент на встрече', 'width' => 1920, 'height' => 1080],
        ['name' => 'Правительственное здание', 'width' => 1600, 'height' => 900],
        ['name' => 'Пресс-конференция', 'width' => 1920, 'height' => 1280],
    ],
    'culture' => [
        ['name' => 'Культурный центр', 'width' => 1920, 'height' => 1080],
        ['name' => 'Концертный зал', 'width' => 1600, 'height' => 900],
        ['name' => 'Выставка', 'width' => 1920, 'height' => 1280],
        ['name' => 'Мугам', 'width' => 1920, 'height' => 1080],
    ],
    'sport' => [
        ['name' => 'Футбольный стадион', 'width' => 1920, 'height' => 1080],
        ['name' => 'Сборная команда', 'width' => 1600, 'height' => 900],
    ],
    'tech' => [
        ['name' => 'Современные технологии', 'width' => 1920, 'height' => 1080],
        ['name' => 'IT офис', 'width' => 1600, 'height' => 900],
        ['name' => 'Инновации', 'width' => 1920, 'height' => 1280],
    ],
    'nature' => [
        ['name' => 'Весенний пейзаж', 'width' => 1920, 'height' => 1080],
        ['name' => 'Цветущие деревья', 'width' => 1600, 'height' => 900],
        ['name' => 'Горы Азербайджана', 'width' => 1920, 'height' => 1280],
    ],
    'city' => [
        ['name' => 'Баку панорама', 'width' => 1920, 'height' => 1080],
        ['name' => 'Пламенные башни', 'width' => 1600, 'height' => 900],
        ['name' => 'Улицы Баку', 'width' => 1920, 'height' => 1280],
        ['name' => 'Приморский бульвар', 'width' => 1920, 'height' => 1080],
    ],
    'economy' => [
        ['name' => 'Деловой центр', 'width' => 1920, 'height' => 1080],
        ['name' => 'Экономический рост', 'width' => 1600, 'height' => 900],
    ],
    'science' => [
        ['name' => 'Научная лаборатория', 'width' => 1920, 'height' => 1080],
        ['name' => 'Исследования', 'width' => 1600, 'height' => 900],
    ],
    'generic' => [
        ['name' => 'Азербайджан', 'width' => 1920, 'height' => 1080],
        ['name' => 'Современный Баку', 'width' => 1600, 'height' => 900],
    ],
];

// Определяем категорию изображения по содержанию поста
function getImageCategory($post) {
    $title = mb_strtolower($post->title);

    if (str_contains($title, 'президент') || str_contains($title, 'министр') || str_contains($title, 'встреч')) {
        return 'news';
    } elseif (str_contains($title, 'культур') || str_contains($title, 'режиссер') || str_contains($title, 'мугам') || str_contains($title, 'музык') || str_contains($title, 'ремесел')) {
        return 'culture';
    } elseif (str_contains($title, 'сборная') || str_contains($title, 'матч') || str_contains($title, 'спорт')) {
        return 'sport';
    } elseif (str_contains($title, 'технолог') || str_contains($title, 'it') || str_contains($title, 'образован')) {
        return 'tech';
    } elseif (str_contains($title, 'весна') || str_contains($title, 'природ') || str_contains($title, 'эколог')) {
        return 'nature';
    } elseif (str_contains($title, 'баку') || str_contains($title, 'транспорт') || str_contains($title, 'город')) {
        return 'city';
    } elseif (str_contains($title, 'эконом') || str_contains($title, 'ввп')) {
        return 'economy';
    } elseif (str_contains($title, 'научн') || str_contains($title, 'ученые') || str_contains($title, 'материал')) {
        return 'science';
    }

    return 'generic';
}

foreach ($posts as $index => $post) {
    $num = $index + 1;
    echo "[$num/{$posts->count()}] Добавляем изображение к: {$post->title}\n";

    // Определяем категорию изображения
    $category = getImageCategory($post);
    $imageList = $images[$category];
    $image = $imageList[array_rand($imageList)];

    $fileName = "featured-{$post->id}-" . Str::slug($image['name']) . ".jpg";

    // Создаем запись в таблице media для featured image
    $mediaId = DB::table('media')->insertGetId([
        'model_type' => 'App\\Models\\Post',
        'model_id' => $post->id,
        'uuid' => Str::uuid(),
        'collection_name' => 'featured',
        'name' => $image['name'],
        'file_name' => $fileName,
        'mime_type' => 'image/jpeg',
        'disk' => 'public',
        'conversions_disk' => 'public',
        'size' => rand(800000, 2500000), // От 800KB до 2.5MB
        'manipulations' => json_encode([]),
        'custom_properties' => json_encode([
            'width' => $image['width'],
            'height' => $image['height'],
        ]),
        'generated_conversions' => json_encode([
            'thumb' => true,
            'medium' => true,
            'large' => true,
        ]),
        'responsive_images' => json_encode([]),
        'order_column' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Обновляем пост, добавляя featured_media_id
    $post->featured_media_id = $mediaId;
    $post->featured_image = "storage/media/{$post->id}/{$fileName}";
    $post->save();

    echo "  ✓ Добавлено изображение: {$image['name']} (категория: {$category})\n";
    echo "  ✓ Media ID: {$mediaId}\n\n";
}

echo "✓ Главные изображения добавлены ко всем постам!\n";
