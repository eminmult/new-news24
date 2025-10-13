<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class LivewireServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Регистрируем в register() чтобы выполнилось раньше Livewire
    }

    public function boot(): void
    {
        // Переопределяем метод temporaryUrl для TemporaryUploadedFile
        // Генерируем обычные URL вместо signed URLs из-за проблемы с двумя доменами
        TemporaryUploadedFile::macro('temporaryUrl', function ($expiration = null) {
            /** @var TemporaryUploadedFile $this */
            // Возвращаем обычный URL без signature
            return url('/livewire/preview-file/' . $this->getFilename());
        });
    }
}
