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
        // Категории кешируются на 1 час
        $categories = Cache::remember('all_categories', 3600, function() {
            return Category::where('is_active', true)
                ->orderBy('order')
                ->get();
        });

        // Посты для слайдера - кеш 5 минут
        $sliderPosts = Cache::remember('home_slider_posts', 300, function() {
            return Post::published()
                ->where('show_in_slider', true)
                ->with(['categories', 'category', 'author'])
                ->latest('published_at')
                ->take(Setting::get('slider_posts_count', 5))
                ->get();
        });

        // Важные новости сегодня - кеш 5 минут
        $importantPosts = Cache::remember('home_important_posts', 300, function() {
            return Post::published()
                ->where('show_in_important_today', true)
                ->with(['categories', 'category', 'author'])
                ->latest('published_at')
                ->take(Setting::get('trending_posts_count', 6))
                ->get();
        });

        // Главные новости для главного блока - кеш 5 минут
        $mainFeaturedPosts = Cache::remember('home_main_featured_posts', 300, function() {
            return Post::published()
                ->where('show_in_main_featured', true)
                ->with(['categories', 'category', 'author'])
                ->latest('published_at')
                ->take(Setting::get('slider_posts_count', 5))
                ->get();
        });

        // Видео посты для youtube-carousel - кеш 10 минут
        $videoPosts = Cache::remember('home_video_posts', 600, function() {
            return Post::published()
                ->where('show_in_types_block', true)
                ->whereHas('types', function($query) {
                    $query->where('slug', 'video');
                })
                ->with(['categories', 'category', 'author', 'types'])
                ->latest('published_at')
                ->take(6)
                ->get();
        });

        // Фото посты - кеш 10 минут
        $photoPosts = Cache::remember('home_photo_posts', 600, function() {
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

        // Последние посты - кешируем на 3 минуты с учетом страницы
        $page = request()->get('page', 1);
        $latestPosts = Cache::remember("home_latest_posts_page_{$page}", 180, function() {
            return Post::published()
                ->with(['categories', 'category', 'author'])
                ->latest('published_at')
                ->paginate(Setting::get('home_posts_count', 15));
        });

        return view('home', compact('categories', 'sliderPosts', 'importantPosts', 'mainFeaturedPosts', 'videoPosts', 'photoPosts', 'latestPosts'));
    }

    public function category($slug)
    {
        // Кешируем категорию на 1 час
        $category = Cache::remember("category_{$slug}", 3600, function() use ($slug) {
            return Category::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();
        });

        // Кешируем список категорий с количеством постов на 30 минут
        $categories = Cache::remember('categories_with_posts_count', 1800, function() {
            return Category::where('is_active', true)
                ->withCount(['posts' => function($query) {
                    $query->published();
                }])
                ->orderBy('order')
                ->get();
        });

        // Посты категории кешируем на 5 минут с учетом страницы
        $page = request()->get('page', 1);
        $posts = Cache::remember("category_{$category->id}_posts_page_{$page}", 300, function() use ($category) {
            return Post::published()
                ->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                })
                ->with(['categories', 'category', 'author'])
                ->latest('published_at')
                ->paginate(Setting::get('category_posts_count', 12));
        });

        // Общие просмотры категории кешируем на 10 минут
        $totalViews = Cache::remember("category_{$category->id}_total_views", 600, function() use ($category) {
            return Post::published()
                ->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                })
                ->sum('views');
        });

        return view('category', compact('category', 'categories', 'posts', 'totalViews'));
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

    public function about()
    {
        // Кешируем статическую страницу "О нас" на 1 час
        $page = Cache::remember('static_page_haqqimizda', 3600, function() {
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

        return view('about', compact('page', 'categories'));
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
