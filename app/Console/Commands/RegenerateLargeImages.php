<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RegenerateLargeImages extends Command
{
    protected $signature = 'media:regenerate-large {--limit=100 : Number of posts to process per run}';

    protected $description = 'Regenerate only missing large image conversions for posts';

    public function handle()
    {
        $limit = $this->option('limit');

        $this->info("Checking for posts with missing large conversions...");

        // Подсчитываем общее количество медиа
        $totalMedia = Media::where('collection_name', 'post-gallery')->count();

        $this->info("Total media items to check: {$totalMedia}");

        $processed = 0;
        $created = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar($totalMedia);
        $bar->start();

        // Обрабатываем по 500 медиа за раз
        Media::where('collection_name', 'post-gallery')
            ->orderBy('id')
            ->chunk(500, function ($mediaItems) use (&$processed, &$created, &$skipped, $limit, $bar) {
                foreach ($mediaItems as $media) {
                    $bar->advance();

                    try {
                        // Проверяем существует ли large конверсия
                        $largePath = $media->getPath('large');

                        if (!file_exists($largePath)) {
                            // Загружаем модель если не загружена
                            if (!$media->model) {
                                $media->load('model');
                            }

                            // Генерируем только large конверсию
                            $media->manipulations = [];

                            // Получаем конверсию из модели
                            if ($media->model && method_exists($media->model, 'getMediaConversion')) {
                                $conversion = $media->model->getMediaConversion('large');

                                if ($conversion) {
                                    app(\Spatie\MediaLibrary\Conversions\FileManipulator::class)
                                        ->createDerivedFiles($media, [$conversion]);

                                    $created++;

                                    if ($created % 100 == 0) {
                                        $this->newLine();
                                        $this->info("Created {$created} large conversions so far...");
                                    }
                                }
                            }
                        } else {
                            $skipped++;
                        }

                        $processed++;

                        if ($limit && $created >= $limit) {
                            return false; // Останавливаем chunk
                        }
                    } catch (\Exception $e) {
                        $this->error("Error processing media {$media->id}: " . $e->getMessage());
                    }
                }
            });

        $bar->finish();
        $this->newLine(2);

        $this->info("Processing complete!");
        $this->info("Total processed: {$processed}");
        $this->info("Large conversions created: {$created}");
        $this->info("Already existed (skipped): {$skipped}");

        return 0;
    }
}
