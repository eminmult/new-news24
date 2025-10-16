<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetDefaultLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Если локаль еще не установлена в сессии, устанавливаем русский
        if (!session()->has('locale')) {
            session()->put('locale', 'ru');
            app()->setLocale('ru');
        } else {
            app()->setLocale(session('locale', 'ru'));
        }

        return $next($request);
    }
}
