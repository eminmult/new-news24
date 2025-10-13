<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Создаем директории для каждого поста
$mediaItems = DB::table('media')->get();

echo "Скачиваем изображения для " . $mediaItems->count() . " медиафайлов...\n\n";

$categories = [
    'news' => 'business',
    'culture' => 'people',
    'sport' => 'sports',
    'tech' => 'technology',
    'nature' => 'nature',
    'city' => 'city',
];

foreach ($mediaItems as $index => $media) {
    $num = $index + 1;
    echo "[$num/{$mediaItems->count()}] Скачиваем: {$media->name}\n";

    // Определяем категорию по имени файла
    $category = 'people';
    if (str_contains(strtolower($media->name), 'президент') || str_contains(strtolower($media->name), 'правительств') || str_contains(strtolower($media->name), 'пресс')) {
        $category = 'business';
    } elseif (str_contains(strtolower($media->name), 'культур') || str_contains(strtolower($media->name), 'концерт') || str_contains(strtolower($media->name), 'мугам') || str_contains(strtolower($media->name), 'выставк')) {
        $category = 'people';
    } elseif (str_contains(strtolower($media->name), 'стадион') || str_contains(strtolower($media->name), 'спорт') || str_contains(strtolower($media->name), 'команд')) {
        $category = 'sports';
    } elseif (str_contains(strtolower($media->name), 'технолог') || str_contains(strtolower($media->name), 'офис') || str_contains(strtolower($media->name), 'инновац')) {
        $category = 'technology';
    } elseif (str_contains(strtolower($media->name), 'весн') || str_contains(strtolower($media->name), 'дерев') || str_contains(strtolower($media->name), 'гор')) {
        $category = 'nature';
    } elseif (str_contains(strtolower($media->name), 'баку') || str_contains(strtolower($media->name), 'башн') || str_contains(strtolower($media->name), 'улиц') || str_contains(strtolower($media->name), 'бульвар') || str_contains(strtolower($media->name), 'панорам')) {
        $category = 'city';
    }

    // Парсим custom_properties для получения размеров
    $customProps = json_decode($media->custom_properties, true);
    $width = $customProps['width'] ?? 1600;
    $height = $customProps['height'] ?? 900;

    // URL для получения placeholder изображения
    $imageUrl = "https://picsum.photos/{$width}/{$height}";

    // Путь для сохранения
    $directory = "public/{$media->model_id}";
    $filePath = "{$directory}/{$media->file_name}";

    // Создаем директорию, если её нет
    if (!Storage::exists($directory)) {
        Storage::makeDirectory($directory);
    }

    // Скачиваем изображение
    try {
        $imageContent = file_get_contents($imageUrl);
        if ($imageContent !== false) {
            Storage::put($filePath, $imageContent);
            echo "  ✓ Сохранено: {$filePath}\n";
        } else {
            echo "  ✗ Ошибка загрузки: {$imageUrl}\n";
        }
    } catch (Exception $e) {
        echo "  ✗ Ошибка: " . $e->getMessage() . "\n";
    }

    // Небольшая задержка, чтобы не перегружать сервис
    usleep(200000); // 0.2 секунды
}

echo "\n✓ Скачивание завершено!\n";
