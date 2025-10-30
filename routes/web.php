<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Models\PostLock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Route для Livewire preview файлов (должен быть ПЕРВЫМ, до всех остальных)
// Обрабатывает временные файлы без проверки signature из-за проблемы с двумя доменами
Route::get('/livewire/preview-file/{filename}', function ($filename) {
    $disk = config('livewire.temporary_file_upload.disk', 'livewire-tmp');
    $diskInstance = Storage::disk($disk);

    if ($diskInstance->exists($filename)) {
        $path = $diskInstance->path($filename);
        if (file_exists($path)) {
            return response()->file($path);
        }
    }

    abort(404, 'File not found');
})->where('filename', '.*')->name('livewire.preview-file');

// Фронтенд маршруты
Route::get('/', [HomeController::class, 'index'])
    ->name('home')
    ->middleware(\Spatie\ResponseCache\Middlewares\CacheResponse::class);
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/api/popular-searches', [HomeController::class, 'popularSearches'])->name('api.popular-searches');
Route::get('/haqqimizda', [HomeController::class, 'about'])->name('about');
Route::get('/elaqe', [HomeController::class, 'contact'])->name('contact');

// Sitemap маршруты (ДОЛЖНЫ БЫТЬ ПЕРЕД catch-all роутом!)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-news.xml', [SitemapController::class, 'news'])->name('sitemap.news');
Route::get('/sitemap-posts-{year}-{month}.xml', [SitemapController::class, 'postsByMonth'])->name('sitemap.posts.month')->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}']);
Route::get('/sitemap-posts-{year}.xml', [SitemapController::class, 'postsByYear'])->name('sitemap.posts.year')->where(['year' => '[0-9]{4}']);
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-images.xml', [SitemapController::class, 'imagesIndex'])->name('sitemap.images');
Route::get('/sitemap-images-{year}-{month}.xml', [SitemapController::class, 'imagesByMonth'])->name('sitemap.images.month')->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}']);
Route::get('/sitemap-images-{year}.xml', [SitemapController::class, 'imagesByYear'])->name('sitemap.images.year')->where(['year' => '[0-9]{4}']);

// LLM.txt для AI-ботов (ChatGPT, Claude, Perplexity)
Route::get('/llm.txt', function () {
    $path = public_path('llm.txt');

    if (!file_exists($path)) {
        abort(404, 'LLM.txt not generated yet. Run: php artisan llm:generate');
    }

    return response()->file($path, [
        'Content-Type' => 'text/plain; charset=utf-8',
        'Cache-Control' => 'public, max-age=1800', // 30 минут
    ]);
})->name('llm.txt');

// Роут для постов (с категорией в URL)
Route::get('/{category}/{slug}', [HomeController::class, 'show'])
    ->name('post')
    ->middleware(\Spatie\ResponseCache\Middlewares\CacheResponse::class);

// Роут для категорий и старых DLE URL-ов (должен быть последним)
Route::get('/{slug}', function ($slug) {
    // Проверяем, является ли это старым DLE URL (формат: /ID-slug.html)
    if (preg_match('/^(\d+)-(.*?)\.html$/', $slug, $matches)) {
        $oldSlug = $matches[2]; // Извлекаем slug без ID и .html

        // Ищем пост по старому URL
        $post = \App\Models\Post::where('old_url', $oldSlug)
            ->with('categories')
            ->first();

        if ($post && $post->categories->isNotEmpty()) {
            $firstCategory = $post->categories->first();
            // Делаем 301 редирект на новый URL
            return redirect()->route('post', [
                'category' => $firstCategory->slug,
                'slug' => $post->slug
            ], 301);
        }

        // Если пост не найден, возвращаем 404
        abort(404);
    }

    // Если это не старый URL, обрабатываем как категорию
    return app()->make(\App\Http\Controllers\HomeController::class)->category($slug);
})->name('category')->middleware(\Spatie\ResponseCache\Middlewares\CacheResponse::class);

// Heartbeat endpoint для поддержания блокировки поста
Route::post('/admin/post-lock/heartbeat/{postId}', function ($postId) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $lock = PostLock::where('post_id', $postId)
        ->where('user_id', Auth::id())
        ->first();

    if ($lock) {
        $lock->update(['last_heartbeat' => now()]);
        return response()->json(['success' => true]);
    }

    return response()->json(['error' => 'Lock not found'], 404);
})->middleware('auth')->name('post-lock.heartbeat');

// Release lock endpoint для снятия блокировки
Route::post('/admin/post-lock/release/{postId}', function ($postId) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    PostLock::where('post_id', $postId)
        ->where('user_id', Auth::id())
        ->delete();

    return response()->json(['success' => true]);
})->middleware('auth')->name('post-lock.release');
