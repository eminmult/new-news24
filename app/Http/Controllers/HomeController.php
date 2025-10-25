<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Config;
use App\Models\Post;
use App\Models\Setting;
use App\Models\StaticPage;
use App\Services\AzerbaijaniSearchNormalizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Категории кешируются на 1 год (инвалидация через Observer)
        $categories = Cache::remember('all_categories', 31536000, function() {
            return Category::where('is_active', true)
                ->orderBy('order')
                ->get();
        });

        // Посты для слайдера - кеш 1 год (инвалидация через Observer)
        $sliderPosts = Cache::remember('home_slider_posts', 31536000, function() {
            return Post::published()
                ->where('show_in_slider', true)
                ->with(['categories', 'category', 'author'])
                ->latest('published_at')
                ->take(Setting::get('slider_posts_count', 5))
                ->get();
        });

        // Важные новости сегодня - кеш 1 год (инвалидация через Observer)
        $importantPosts = Cache::remember('home_important_posts', 31536000, function() {
            return Post::published()
                ->where('show_in_important_today', true)
                ->with(['categories', 'category', 'author'])
                ->latest('published_at')
                ->take(Setting::get('trending_posts_count', 6))
                ->get();
        });

        // Главные новости для главного блока - кеш 1 год (инвалидация через Observer)
        $mainFeaturedPosts = Cache::remember('home_main_featured_posts', 31536000, function() {
            return Post::published()
                ->where('show_in_main_featured', true)
                ->with(['categories', 'category', 'author'])
                ->latest('published_at')
                ->take(Setting::get('slider_posts_count', 5))
                ->get();
        });

        // Видео посты для youtube-carousel - кеш 1 год (инвалидация через Observer)
        $videoPosts = Cache::remember('home_video_posts', 31536000, function() {
            return Post::published()
                ->where('show_in_video_section', true)
                ->with(['categories', 'category', 'author', 'types'])
                ->latest('published_at')
                ->take(6)
                ->get();
        });

        // Фото посты - кеш 1 год (инвалидация через Observer)
        $photoPosts = Cache::remember('home_photo_posts', 31536000, function() {
            return Post::published()
                ->where('show_in_types_block', true)
                ->whereHas('types', function($query) {
                    $query->where('slug', 'photo');
                })
                ->with(['categories', 'category', 'author', 'types'])
                ->latest('published_at')
                ->take(6)
                ->get();
        });

        // Последние посты - кешируем на 1 год с учетом страницы (инвалидация через Observer)
        $page = request()->get('page', 1);
        $latestPosts = Cache::remember("home_latest_posts_page_{$page}", 31536000, function() {
            return Post::published()
                ->with(['categories', 'category', 'author'])
                ->latest('published_at')
                ->paginate(Setting::get('home_posts_count', 15));
        });

        return view('home', compact('categories', 'sliderPosts', 'importantPosts', 'mainFeaturedPosts', 'videoPosts', 'photoPosts', 'latestPosts'));
    }

    public function category($slug)
    {
        // Кешируем категорию на 1 год (инвалидация через Observer)
        $category = Cache::remember("category_{$slug}", 31536000, function() use ($slug) {
            return Category::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();
        });

        // Посты категории кешируем на 1 год с учетом страницы (инвалидация через Observer)
        $page = request()->get('page', 1);
        $posts = Cache::remember("category_{$category->id}_posts_page_{$page}", 31536000, function() use ($category) {
            return Post::published()
                ->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                })
                ->with(['category', 'author'])
                ->latest('published_at')
                ->paginate(Setting::get('category_posts_count', 12));
        });

        // Кешируем количество постов за сегодня на 1 день (обновляется каждый день автоматически)
        $todayPostsCount = Cache::remember("category_{$category->id}_today_posts_count", 86400, function() use ($category) {
            return Post::published()
                ->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                })
                ->where('published_at', '>=', now()->startOfDay())
                ->count();
        });

        return view('category', compact('category', 'posts', 'todayPostsCount'));
    }

    public function show($category, $slug)
    {
        // Кешируем пост на 10 минут
        $post = Cache::remember("post_{$category}_{$slug}", 600, function() use ($slug, $category) {
            return Post::published()
                ->where('slug', $slug)
                ->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.slug', $category);
                })
                ->with(['categories', 'author', 'tags', 'widgets'])
                ->firstOrFail();
        });

        // Счетчик просмотров обновляется отдельно (не кешируется)
        $post->incrementViews();

        // Категории уже кешируются в ViewServiceProvider, но для единообразия
        $categories = Cache::remember('all_categories', 3600, function() {
            return Category::where('is_active', true)
                ->orderBy('order')
                ->get();
        });

        // Кешируем похожие посты на 30 минут
        $relatedPosts = Cache::remember("post_{$post->id}_related", 1800, function() use ($post) {
            return Post::published()
                ->whereHas('categories', function($q) use ($post) {
                    $q->whereIn('categories.id', $post->categories->pluck('id'));
                })
                ->where('id', '!=', $post->id)
                ->latest('published_at')
                ->take(Setting::get('related_posts_count', 6))
                ->get();
        });

        return view('post', compact('post', 'categories', 'relatedPosts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        // Записываем поисковый запрос
        if (!empty($query)) {
            \App\Models\SearchQuery::recordQuery($query);
        }

        $categories = Cache::remember('all_categories', 3600, function() {
            return Category::where('is_active', true)
                ->orderBy('order')
                ->get();
        });

        // Используем Meilisearch через Scout для быстрого поиска
        if (!empty($query)) {
            // Ищем по оригинальному запросу
            // Meilisearch сам найдет в оригинальных и латинизированных полях
            $posts = Post::search($query)
                ->query(function($builder) {
                    // Загружаем связи для найденных постов
                    $builder->with(['category', 'author']);
                })
                ->paginate(Setting::get('search_posts_count', 12));
        } else {
            // Если запрос пустой, показываем последние посты
            $posts = Post::published()
                ->with(['category', 'author'])
                ->latest('published_at')
                ->paginate(Setting::get('search_posts_count', 12));
        }

        return view('search', compact('query', 'categories', 'posts'));
    }

    public function popularSearches()
    {
        $queries = \App\Models\SearchQuery::getTopQueries(5);
        return response()->json($queries);
    }

    public function about()
    {
        // Кешируем статическую страницу "О нас" на 7 дней (604800 секунд)
        $page = Cache::remember('static_page_haqqimizda', 604800, function() {
            return StaticPage::active()
                ->where('slug', 'haqqimizda')
                ->firstOrFail();
        });

        // Категории уже кешируются
        $categories = Cache::remember('all_categories', 3600, function() {
            return Category::where('is_active', true)
                ->orderBy('order')
                ->get();
        });

        // Кешируем авторов на 24 часа (86400 секунд)
        $authors = Cache::remember('about_page_authors', 86400, function() {
            return \App\Models\User::where('role', 'author')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });

        return view('about', compact('page', 'categories', 'authors'));
    }

    public function contact()
    {
        // Кешируем статическую страницу "Контакты" на 1 час
        $page = Cache::remember('static_page_elaqe', 3600, function() {
            return StaticPage::active()
                ->where('slug', 'elaqe')
                ->firstOrFail();
        });

        // Категории уже кешируются
        $categories = Cache::remember('all_categories', 3600, function() {
            return Category::where('is_active', true)
                ->orderBy('order')
                ->get();
        });

        return view('contact', compact('page', 'categories'));
    }
}
