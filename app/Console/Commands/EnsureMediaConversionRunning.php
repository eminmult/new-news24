<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EnsureMediaConversionRunning extends Command
{
    protected $signature = 'media:ensure-conversion-running';
    protected $description = 'Check and restart media large conversion if stopped';

    public function handle()
    {
        $this->info('Checking media conversion status...');

        // Проверяем есть ли медиа без large конвертации
        $missingCount = Media::whereRaw("JSON_EXTRACT(generated_conversions, '$.large') IS NULL OR JSON_EXTRACT(generated_conversions, '$.large') = false")
            ->count();

        if ($missingCount === 0) {
            $this->info('All media has large conversions. Nothing to do.');
            return 0;
        }

        $this->info("Found {$missingCount} media items without large conversion");

        // Проверяем когда было последнее обновление
        $lastUpdate = Media::orderBy('updated_at', 'desc')->first();
        $minutesSinceUpdate = $lastUpdate ? now()->diffInMinutes($lastUpdate->updated_at) : 999;

        $this->info("Last media update: {$minutesSinceUpdate} minutes ago");

        // Если обновлений не было больше 10 минут, перезапускаем
        if ($minutesSinceUpdate > 10) {
            $this->warn('Conversion appears to be stuck. Restarting...');

            // Запускаем процесс в фоне
            $command = 'nohup php ' . base_path('artisan') . ' media:regenerate-large --limit=10000 > ' . storage_path('logs/media-conversion.log') . ' 2>&1 &';
            exec($command);

            $this->info('Conversion process restarted');

            // Логируем в файл
            \Log::channel('daily')->info('Media conversion restarted', [
                'missing_count' => $missingCount,
                'minutes_since_update' => $minutesSinceUpdate,
            ]);

            return 0;
        }

        $this->info('Conversion is running normally');
        return 0;
    }
}
