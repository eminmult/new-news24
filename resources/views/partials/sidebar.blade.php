<!-- Sidebar -->
<aside class="category-sidebar">
    <!-- Trending Section -->
    <div class="sidebar-block trending-block">
        <h3 class="sidebar-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
            </svg>
            Günün trendi
        </h3>
        <div class="trending-list">
            @php
                // Автоматический ТОП новостей за последние 3 дня по просмотрам
                $trendingPosts = \App\Models\Post::published()
                    ->where('published_at', '>=', now()->subDays(3))
                    ->with(['category'])
                    ->orderBy('views', 'desc')
                    ->take(\App\Models\Setting::get('trending_posts_count', 5))
                    ->get();
            @endphp
            @foreach($trendingPosts as $index => $trendingPost)
            <a href="{{ $trendingPost->url }}" class="trending-item">
                <span class="trending-number">#{{ $index + 1 }}</span>
                <div class="trending-content">
                    <h4 class="trending-title">{{ $trendingPost->title }}</h4>
                    <span class="trending-views">{{ number_format($trendingPost->views) }} baxış</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Other Categories -->
    <div class="sidebar-block categories-block">
        <h3 class="sidebar-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
            </svg>
            Digər Kateqoriyalar
        </h3>
        <div class="sidebar-categories">
            @php
                $sidebarCategories = \App\Models\Category::where('is_active', true)
                    ->where('show_in_menu', true)
                    ->withCount(['posts' => function($query) {
                        $query->published();
                    }])
                    ->orderBy('order')
                    ->get();
            @endphp
            @foreach($sidebarCategories as $sidebarCategory)
            <a href="{{ route('category', $sidebarCategory->slug) }}" class="sidebar-category">
                <span class="sidebar-category-name">{{ $sidebarCategory->name }}</span>
                <span class="sidebar-category-count">{{ $sidebarCategory->posts_count }}</span>
            </a>
            @endforeach
        </div>
    </div>
</aside>
