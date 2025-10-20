<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\Image\Enums\Fit;

class GenerateLargeConversion implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $mediaId
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $media = Media::find($this->mediaId);

        if (!$media) {
            return;
        }

        // Проверяем, существует ли уже large конвертация
        if ($media->hasGeneratedConversion('large')) {
            return;
        }

        try {
            // Создаем конвертацию точно так же как в Post модели
            $conversion = Conversion::create('large')
                ->format('webp')
                ->fit(Fit::Crop, 1200, 800)
                ->quality(85)
                ->performOnCollections('post-gallery', 'post-content-images')
                ->nonQueued();

            // Генерируем конвертацию
            app(\Spatie\MediaLibrary\Conversions\FileManipulator::class)
                ->createDerivedFiles($media, [$conversion]);

            // Проверяем что файл создался
            $largePath = $media->getPath('large');
            if (!file_exists($largePath)) {
                throw new \Exception('Large file was not created at: ' . $largePath);
            }

            \Log::info('Large conversion created for media ' . $this->mediaId);

        } catch (\Exception $e) {
            \Log::error('Failed to generate large conversion for media ' . $this->mediaId, [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
