<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Получаем все медиафайлы
$mediaItems = DB::table('media')->get();

echo "Скачиваем изображения для " . $mediaItems->count() . " медиафайлов...\n\n";

$storageBasePath = '/var/www/html/storage/app/public';

foreach ($mediaItems as $index => $media) {
    $num = $index + 1;
    echo "[$num/{$mediaItems->count()}] Скачиваем: {$media->name}\n";

    // Парсим custom_properties для получения размеров
    $customProps = json_decode($media->custom_properties, true);
    $width = $customProps['width'] ?? 1600;
    $height = $customProps['height'] ?? 900;

    // URL для получения placeholder изображения
    $imageUrl = "https://picsum.photos/{$width}/{$height}";

    // Путь для сохранения
    $directory = "{$storageBasePath}/{$media->model_id}";
    $filePath = "{$directory}/{$media->file_name}";

    // Создаем директорию, если её нет
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    // Скачиваем с помощью curl
    $ch = curl_init($imageUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $imageContent = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200 && $imageContent !== false) {
        file_put_contents($filePath, $imageContent);
        echo "  ✓ Сохранено: {$media->model_id}/{$media->file_name}\n";
    } else {
        echo "  ✗ Ошибка загрузки (HTTP {$httpCode})\n";
    }

    // Небольшая задержка
    usleep(300000); // 0.3 секунды
}

echo "\n✓ Скачивание завершено!\n";
