<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;
use Illuminate\Support\Facades\DB;

// Получаем посты с типом "Фото"
$photoPosts = Post::whereHas('types', function($query) {
    $query->where('slug', 'photo');
})->get();

echo "Найдено {$photoPosts->count()} постов с типом 'Фото'\n\n";

// Список тестовых изображений (используем placeholder сервис)
$placeholderImages = [
    ['name' => 'Панорама Баку', 'width' => 1920, 'height' => 1080],
    ['name' => 'Старый город', 'width' => 1920, 'height' => 1280],
    ['name' => 'Современная архитектура', 'width' => 1600, 'height' => 900],
    ['name' => 'Набережная', 'width' => 1920, 'height' => 1080],
    ['name' => 'Пламенные башни', 'width' => 1200, 'height' => 1600],
    ['name' => 'Девичья башня', 'width' => 1080, 'height' => 1350],
    ['name' => 'Приморский бульвар', 'width' => 1920, 'height' => 1080],
    ['name' => 'Нагорный парк', 'width' => 1600, 'height' => 900],
    ['name' => 'Центр Гейдара Алиева', 'width' => 1920, 'height' => 1280],
    ['name' => 'Фонтанная площадь', 'width' => 1920, 'height' => 1080],
];

foreach ($photoPosts as $index => $post) {
    $num = $index + 1;
    echo "[$num/{$photoPosts->count()}] Добавляем галерею к: {$post->title}\n";

    // Определяем количество фото в галерее (от 3 до 8)
    $photoCount = rand(3, 8);

    for ($i = 0; $i < $photoCount; $i++) {
        $image = $placeholderImages[array_rand($placeholderImages)];
        $fileName = "photo-{$post->id}-" . ($i + 1) . ".jpg";

        // Создаем запись в таблице media
        DB::table('media')->insert([
            'model_type' => 'App\\Models\\Post',
            'model_id' => $post->id,
            'uuid' => \Illuminate\Support\Str::uuid(),
            'collection_name' => 'gallery',
            'name' => $image['name'],
            'file_name' => $fileName,
            'mime_type' => 'image/jpeg',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => rand(500000, 3000000), // От 500KB до 3MB
            'manipulations' => json_encode([]),
            'custom_properties' => json_encode([
                'width' => $image['width'],
                'height' => $image['height'],
            ]),
            'generated_conversions' => json_encode([]),
            'responsive_images' => json_encode([]),
            'order_column' => $i + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    echo "  ✓ Добавлено {$photoCount} фотографий в галерею\n\n";
}

echo "✓ Галереи добавлены!\n";
