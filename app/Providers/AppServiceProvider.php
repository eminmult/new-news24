<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use App\Models\PostType;
use App\Models\StaticPage;
use App\Observers\PostObserver;
use App\Observers\CategoryObserver;
use App\Observers\SettingObserver;
use App\Observers\ActivityLogObserver;
use App\Observers\MediaObserver;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Устанавливаем локаль Carbon для правильного форматирования дат
        \Carbon\Carbon::setLocale('az');

        // Регистрируем Observer для автоматической очистки кеша sitemap
        Post::observe(PostObserver::class);
        Category::observe(CategoryObserver::class);
        Setting::observe(SettingObserver::class);

        // Регистрируем ActivityLogObserver для всех моделей
        Post::observe(ActivityLogObserver::class);
        Category::observe(ActivityLogObserver::class);
        Tag::observe(ActivityLogObserver::class);
        User::observe(ActivityLogObserver::class);
        PostType::observe(ActivityLogObserver::class);
        StaticPage::observe(ActivityLogObserver::class);

        // Регистрируем MediaObserver для отслеживания изменений медиа (аватарки, галереи и т.д.)
        Media::observe(MediaObserver::class);

        // Относительные URL /storage работают корректно на обоих доменах
        // (edm.news24.az - админка, news24.az - фронтенд)
    }
}
