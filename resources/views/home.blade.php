@extends('layouts.app')

@php
    $mainInfo = \App\Models\MainInfo::getInstance();
    $siteName = $mainInfo?->site_name ?? 'News24.az';
@endphp

@section('title', $siteName . ' - Əsas səhifə')

@section('seo')
    <x-seo
        :title="($mainInfo?->meta_title ?? $siteName) . ' - Azərbaycanın aparıcı xəbər portalı'"
        :description="$mainInfo?->meta_description ?? 'Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar. Siyasət, iqtisadiyyat, idman, mədəniyyət və daha çox.'"
        :keywords="$mainInfo?->meta_keywords ?? 'xəbərlər, azərbaycan xəbərləri, son xəbərlər, günün xəbərləri, news24.az, siyasət, iqtisadiyyat, idman'"
        :ogType="'website'"
        :ogTitle="$siteName . ' - Azərbaycanın aparıcı xəbər portalı'"
        :ogDescription="'Azərbaycanın ən son və etibarlı xəbər mənbəyi. Siyasət, iqtisadiyyat, idman, mədəniyyət və daha çoxu haqqında gündəlik yeniliklər.'"
        :ogImage="asset('images/logo-cropped.png')"
        :ogUrl="route('home')"
        :canonical="route('home')"
        :twitterCard="'summary_large_image'"
        :twitterTitle="$siteName . ' - Son xəbərlər'"
        :twitterDescription="'Azərbaycanın ən son xəbərləri bir yerdə'"
    />
@endsection

@section('schema')
    {{-- Website Schema --}}
    <x-schema
        type="website"
    />

    {{-- Organization Schema --}}
    <x-schema
        type="organization"
        :socialLinks="[
            'instagram' => config_value('INSTAGRAM'),
            'facebook' => config_value('FACEBOOK'),
            'youtube' => config_value('YOUTUBE'),
            'telegram' => config_value('TELEGRAM'),
            'tiktok' => config_value('TIKTOK'),
            'phone' => config_value('PHONE'),
        ]"
    />

    {{-- WebPage Schema --}}
    <x-schema
        type="webpage"
        :pageTitle="$siteName . ' - Azərbaycanın aparıcı xəbər portalı'"
        :pageDescription="$mainInfo?->meta_description ?? 'Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar'"
    />

    {{-- CollectionPage Schema with Latest Posts --}}
    @if($latestPosts && $latestPosts->isNotEmpty())
    <x-schema
        type="collectionpage"
        :posts="$latestPosts"
        :pageTitle="$siteName . ' - Son xəbərlər'"
        :pageDescription="'Azərbaycanın ən son və aktual xəbərləri'"
    />
    @endif

    {{-- Breadcrumb Schema --}}
    <x-schema
        type="breadcrumb"
        :breadcrumbs="[
            ['name' => 'Əsas səhifə', 'url' => route('home')]
        ]"
    />
@endsection

@section('content')
<main class="main">
    <!-- Trending Topics Carousel -->
    @if(request()->get('page', 1) == 1 && $importantPosts && $importantPosts->isNotEmpty())
    <section class="trending-section">
        <div class="container">
            <div class="trending-wrapper">
                <div class="trending-nav-buttons">
                    <button class="trending-nav-btn prev-trending" aria-label="Əvvəlki">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M12 15l-5-5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <button class="trending-nav-btn next-trending" aria-label="Növbəti">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M8 15l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
                <div class="trending-carousel">
                    <div class="trending-track">
                        @foreach($importantPosts as $index => $post)
                        <div class="trending-card">
                            <div class="trending-image @if(!$post->featured_image_thumb) img-gradient-{{ ($index % 8) + 1 }} @endif">
                                @if($post->featured_image_thumb)
                                <img src="{{ $post->featured_image_thumb }}" alt="{{ $post->title }}" loading="lazy">
                                @endif
                                <span class="trending-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="trending-content">
                                @if($post->main_category)
                                <span class="category-tag category-{{ $post->main_category->id }}">
                                    {{ $post->main_category->name }}
                                </span>
                                @endif
                                <h3><a href="{{ $post->url }}">{{ $post->title }}</a></h3>
                                <div class="card-meta">
                                    <span>📈 {{ number_format($post->views) }} baxış</span>
                                    <span>{{ $post->published_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Top Banner Ad Section -->
    <section class="top-banner-ad-section" style="padding: 20px 0;">
        <div class="container">
            @php
                $topBannerAd = config_value('TOP_BANNER_AD_DESKTOP', '/images/ad-banner-1080x160.svg');
                $topBannerAdLink = config_value('TOP_BANNER_AD_LINK_DESKTOP', '#');
            @endphp
            <a href="{{ $topBannerAdLink }}" target="_blank" rel="noopener" style="display: block; max-width: 1080px; margin: 0 auto;">
                <img src="{{ $topBannerAd }}" alt="Reklam" style="width: 100%; height: auto; border-radius: 8px; display: block;" loading="lazy">
            </a>
        </div>
    </section>

    <!-- Mobile Top Banner -->
    <section class="mobile-top-banner" style="padding: 20px 0; display: none;">
        <div class="container">
            @php
                $mobileBanner = config_value('TOP_BANNER_MOBILE', '/images/ad-banner-430x200.svg');
                $mobileBannerLink = config_value('TOP_BANNER_MOBILE_LINK', '#');
            @endphp
            <a href="{{ $mobileBannerLink }}" target="_blank" rel="noopener" style="display: block; max-width: 430px; margin: 0 auto;">
                <img src="{{ $mobileBanner }}" alt="Reklam" style="width: 100%; height: auto; border-radius: 12px; display: block;" loading="lazy">
            </a>
        </div>
    </section>

    <!-- Main Featured Section -->
    @if(request()->get('page', 1) == 1 && $sliderPosts && $sliderPosts->isNotEmpty())
    <section class="main-featured-section">
        <div class="container">
            <div class="main-featured-wrapper">
                <!-- Featured Slider (75%) -->
                <div class="main-featured-slider-wrapper">
                    <button class="main-featured-nav-btn prev-main-featured" aria-label="Əvvəlki">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>

                    <div class="main-featured-slider">
                        <div class="main-featured-track">
                            @foreach($sliderPosts as $post)
                            <article class="main-featured-card">
                                <div class="card-image">
                                    @if($post->featured_image_large)
                                        <img src="{{ $post->featured_image_large }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                                    @else
                                        <div class="img-gradient-{{ ($loop->index % 8) + 1 }}" style="width: 100%; height: 100%;"></div>
                                    @endif
                                </div>
                                <div class="card-content">
                                    <div class="card-header">
                                        @if($post->main_category)
                                        <span class="category-badge category-{{ $post->main_category->id }}">
                                            {{ $post->main_category->name }}
                                        </span>
                                        @endif
                                    </div>
                                    <h3 class="card-title"><a href="{{ $post->url }}">{{ $post->title }}</a></h3>
                                    <span class="card-date">{{ format_date_az($post->published_at, 'd F Y, H:i') }}</span>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </div>

                    <button class="main-featured-nav-btn next-main-featured" aria-label="Növbəti">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <!-- Side Image (25%) - Advertising Banner -->
                @php
                    $adBanner = config_value('MAIN_FEATURED_AD_BANNER_DESKTOP', '/images/ad-banner-264x528.png');
                @endphp
                @if($adBanner)
                <a href="{{ config_value('MAIN_FEATURED_AD_BANNER_LINK_DESKTOP', '#') }}" class="side-banner-wrapper" target="_blank" rel="noopener" style="display: block; width: 100%; height: 100%;">
                    <img src="{{ $adBanner }}" alt="Reklam" class="main-featured-side-image" style="width: 100%; height: 100%; object-fit: fill; border-radius: 20px;" loading="lazy">
                </a>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- Breaking News Ticker -->
    @if($latestPosts && $latestPosts->count() > 0)
    <section class="breaking-news" @if(request()->get('page', 1) != 1) style="margin-top: 40px;" @endif>
        <div class="container">
            <div class="ticker-wrapper">
                <span class="ticker-label">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M9 0L3 9h4l-1 7 6-9H8l1-7z"/>
                    </svg>
                    Təcili xəbərlər
                </span>
                <div class="ticker-overflow">
                    <div class="ticker-content">
                        @foreach($latestPosts->take(10) as $post)
                            <span class="ticker-item">
                                <a href="{{ $post->url }}">{{ $post->title }}</a>
                            </span>
                        @endforeach
                        {{-- Дублируем контент для бесконечного скролла --}}
                        @foreach($latestPosts->take(10) as $post)
                            <span class="ticker-item">
                                <a href="{{ $post->url }}">{{ $post->title }}</a>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- YouTube Carousel Section -->
    @if(request()->get('page', 1) == 1 && $videoPosts && $videoPosts->isNotEmpty())
    <section class="youtube-carousel-section">
        <div class="container">
            <div class="youtube-carousel-header">
                <h2 class="section-title">
                    Video xəbərlər
                </h2>
            </div>

            <div class="youtube-carousel-wrapper">
                <button class="youtube-nav-btn prev-youtube" aria-label="Əvvəlki">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>

                <div class="youtube-carousel">
                    <div class="youtube-carousel-track">
                        @foreach($videoPosts as $post)
                        <article class="yt-card">
                            <a href="{{ $post->url }}" class="yt-thumbnail">
                                @if($post->featured_image_thumb)
                                    <img src="{{ $post->featured_image_thumb }}" alt="{{ $post->title }}" class="yt-thumbnail-bg" loading="lazy">
                                @else
                                    <div class="img-gradient-{{ ($loop->index % 8) + 1 }} yt-thumbnail-bg"></div>
                                @endif
                                <div class="yt-play">
                                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                        <circle cx="24" cy="24" r="24" fill="rgba(255, 255, 255, 0.95)"/>
                                        <path d="M19 15l15 9-15 9V15z" fill="#ef4444"/>
                                    </svg>
                                </div>
                                <h3 class="yt-title">{{ $post->title }}</h3>
                            </a>
                        </article>
                        @endforeach
                    </div>
                </div>

                <button class="youtube-nav-btn next-youtube" aria-label="Növbəti">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>
    @endif

    <!-- News Grid -->
    <section class="news-grid-section">
        <div class="container">
            <div class="grid-layout">
                <div class="main-column">
                    <div class="section-header">
                        <h2 class="section-title">Son xəbərlər</h2>
                    </div>

                    <div class="news-cards-grid">
                @foreach($latestPosts as $post)
                <article class="news-card">
                    <a href="{{ $post->url }}">
                        <div class="card-image">
                            @if($post->featured_image_thumb)
                                <img src="{{ $post->featured_image_thumb }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                            @else
                                <div class="img-gradient-{{ ($loop->index % 8) + 1 }}" style="width: 100%; height: 100%;"></div>
                            @endif
                            <span class="news-card-date">
                                @if($post->published_at->isToday())
                                    {{ $post->published_at->format('H:i') }}
                                @elseif($post->published_at->year == now()->year)
                                    {{ format_date_az($post->published_at, 'd M H:i') }}
                                @else
                                    {{ format_date_az($post->published_at, 'd M H:i, Y') }}
                                @endif
                            </span>
                        </div>

                        @if($post->main_category)
                        <span class="category-badge category-{{ $post->main_category->id }}">
                            {{ $post->main_category->name }}
                        </span>
                        @endif

                        <div class="card-content">
                            <h3 class="card-title">{{ $post->title }}</h3>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($latestPosts->hasPages())
            <div class="pagination">
                @if($latestPosts->onFirstPage())
                    <button class="pagination-btn pagination-prev" disabled>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M10 12l-4-4 4-4"/>
                        </svg>
                    </button>
                @else
                    <a href="{{ $latestPosts->previousPageUrl() }}" class="pagination-btn pagination-prev">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M10 12l-4-4 4-4"/>
                        </svg>
                    </a>
                @endif

                @php
                    $currentPage = $latestPosts->currentPage();
                    $lastPage = $latestPosts->lastPage();

                    // Показываем 5 страниц: текущую, 2 до и 2 после
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);

                    // Корректируем если близко к началу или концу
                    if ($end - $start < 4) {
                        if ($start == 1) {
                            $end = min($lastPage, 5);
                        } else {
                            $start = max(1, $lastPage - 4);
                        }
                    }
                @endphp

                @if($start > 1)
                    <a href="{{ $latestPosts->url(1) }}" class="pagination-btn pagination-page">1</a>
                    @if($start > 2)
                        <span class="pagination-dots">...</span>
                    @endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        <button class="pagination-btn pagination-page active">{{ $page }}</button>
                    @else
                        <a href="{{ $latestPosts->url($page) }}" class="pagination-btn pagination-page">{{ $page }}</a>
                    @endif
                @endfor

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <span class="pagination-dots">...</span>
                    @endif
                    <a href="{{ $latestPosts->url($lastPage) }}" class="pagination-btn pagination-page">{{ $lastPage }}</a>
                @endif

                @if($latestPosts->hasMorePages())
                    <a href="{{ $latestPosts->nextPageUrl() }}" class="pagination-btn pagination-next">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M6 12l4-4-4-4"/>
                        </svg>
                    </a>
                @else
                    <button class="pagination-btn pagination-next" disabled>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M6 12l4-4-4-4"/>
                        </svg>
                    </button>
                @endif
            </div>
            @endif
                </div>
                {{-- End main-column --}}
            </div>
            {{-- End grid-layout --}}
        </div>
    </section>
</main>
@endsection
