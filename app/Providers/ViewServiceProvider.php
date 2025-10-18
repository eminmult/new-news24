<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            // Кеш категорий для меню на 1 час
            $categories = Cache::remember('menu_categories', 3600, function () {
                return Category::where('is_active', true)
                    ->where('show_in_menu', true)
                    ->orderBy('order')
                    ->get();
            });

            // Social media links from configs (уже кешируются в Config модели)
            $socialLinks = [
                'instagram' => config_value('INSTAGRAM'),
                'facebook' => config_value('FACEBOOK'),
                'youtube' => config_value('YOUTUBE'),
                'tiktok' => config_value('TIKTOK'),
                'telegram' => config_value('TELEGRAM'),
                'twitter' => config_value('TWITTER'),
                'whatsapp' => config_value('WHATSAPP'),
                'phone' => config_value('PHONE'),
                'live' => config_value('LIVE'),
            ];

            // Main site info (кешируется навсегда в модели)
            $mainInfo = \App\Models\MainInfo::getInstance();

            $view->with([
                'categories' => $categories,
                'socialLinks' => $socialLinks,
                'mainInfo' => $mainInfo,
            ]);
        });
    }
}
