@extends('layouts.app')

@php
    $mainInfo = \App\Models\MainInfo::getInstance();
    $siteName = $mainInfo?->site_name ?? 'OLAY.az';
@endphp

@section('title', $siteName . ' - Əsas səhifə')

@section('seo')
    <x-seo
        :title="($mainInfo?->meta_title ?? $siteName) . ' - Azərbaycanın aparıcı xəbər portalı'"
        :description="$mainInfo?->meta_description ?? 'Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar. Siyasət, iqtisadiyyat, idman, mədəniyyət və daha çox.'"
        :keywords="$mainInfo?->meta_keywords ?? 'xəbərlər, azərbaycan xəbərləri, son xəbərlər, günün xəbərləri, olay.az, siyasət, iqtisadiyyat, idman'"
        :ogType="'website'"
        :ogImage="asset('images/logo-cropped.png')"
        :canonical="route('home')"
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
@endsection

@section('content')
<main class="main">
    <!-- Hero Slider -->
    @if($sliderPosts->isNotEmpty())
    <section class="hero-slider">
        <div class="slider-container">
            @foreach($sliderPosts as $post)
            <div class="slide {{ $loop->first ? 'active' : '' }}">
                @php
                    $firstMedia = $post->getMedia('post-gallery')->first();
                    $imageUrl = $firstMedia ? $firstMedia->getUrl('webp') : asset('images/placeholder.jpg');
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $post->title }}">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <div class="container">
                        @if($post->main_category)
                        <span class="category-badge" data-category-id="{{ $post->main_category->id }}" style="background-color: {{ $post->main_category->color }};">{{ $post->main_category->name }}</span>
                        @endif
                        <h1 class="slide-title"><a href="{{ $post->url }}">{{ $post->title }}</a></h1>
                        @if($post->author)
                        <div class="news-author">
                            <img src="{{ $post->author->avatar_thumb }}" alt="{{ $post->author->name }}" class="author-avatar" loading="lazy">
                            <div class="author-info">
                                <span class="author-name">{{ $post->author->name }}</span>
                                <span class="publish-date">{{ $post->published_at->translatedFormat('d F Y, H:i') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Slider Navigation -->
        <button class="slider-arrow slider-prev" aria-label="Əvvəlki slayd">‹</button>
        <button class="slider-arrow slider-next" aria-label="Növbəti slayd">›</button>

        <!-- Slider Dots -->
        <div class="slider-dots">
            @foreach($sliderPosts as $post)
            <span class="dot {{ $loop->first ? 'active' : '' }}" data-slide="{{ $loop->index }}"></span>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Today's Important News Section -->
    @if($importantPosts->isNotEmpty())
    <section class="section-highlights">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">⭐ BUGÜNÜN ƏN ÖNƏMLİ XƏBƏRLƏRİ</h2>
            </div>

            <div class="today-grid">
                @php
                    $mainPost = $importantPosts->first();
                    $smallPosts = $importantPosts->slice(1, 3);
                @endphp

                {{-- Main Large Card --}}
                @if($mainPost)
                <article class="today-card-large">
                    <div class="today-image">
                        @php
                            $mainMedia = $mainPost->getMedia('post-gallery')->first();
                            $mainImageUrl = $mainMedia ? $mainMedia->getUrl('medium') : '/images/placeholder.jpg';
                        @endphp
                        <img src="{{ $mainImageUrl }}" alt="{{ $mainPost->title }}" loading="lazy">
                        @if($mainPost->main_category)
                        <span class="category-badge" data-category-id="{{ $mainPost->main_category->id }}" style="background-color: {{ $mainPost->main_category->color }};">
                            {{ $mainPost->main_category->name }}
                        </span>
                        @endif
                    </div>
                    <div class="today-content">
                        <h2 class="today-title-large">
                            <a href="{{ $mainPost->url }}">{{ $mainPost->title }}</a>
                        </h2>
                        @if($mainPost->author)
                        <div class="news-author">
                            <img src="{{ $mainPost->author->avatar_thumb }}"
                                 alt="{{ $mainPost->author->name }}"
                                 class="author-avatar" loading="lazy">
                            <div class="author-info">
                                <span class="author-name">{{ $mainPost->author->name }}</span>
                                <span class="publish-date">{{ $mainPost->published_at->translatedFormat('d F Y, H:i') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </article>
                @endif

                {{-- Small Cards Grid --}}
                @if($smallPosts->isNotEmpty())
                <div class="today-grid-small">
                    @foreach($smallPosts as $post)
                    <article class="today-card-small">
                        <div class="today-image-small">
                            @if($post->featured_image_thumb)
                                <img src="{{ $post->featured_image_thumb }}"
                                     alt="{{ $post->title }}" loading="lazy">
                            @else
                                <img src="/images/placeholder.jpg" alt="{{ $post->title }}" loading="lazy">
                            @endif
                            @if($post->main_category)
                            <span class="category-badge" data-category-id="{{ $post->main_category->id }}" style="background-color: {{ $post->main_category->color }};">
                                {{ $post->main_category->name }}
                            </span>
                            @endif
                        </div>
                        <div class="today-content-small">
                            <h3 class="today-title-small">
                                <a href="{{ $post->url }}">{{ $post->title }}</a>
                            </h3>
                            @if($post->author)
                            <div class="news-author">
                                <img src="{{ $post->author->avatar_thumb }}"
                                     alt="{{ $post->author->name }}"
                                     class="author-avatar" loading="lazy">
                                <div class="author-info">
                                    <span class="author-name">{{ $post->author->name }}</span>
                                    <span class="publish-date">{{ $post->published_at->translatedFormat('d F Y') }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- Media Section (FOTO-VIDEO) -->
    @if($mediaPosts->isNotEmpty())
    <section class="section-media">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">📸 FOTO-VİDEO</h2>
            </div>

            <div class="media-showcase">
                @php
                    // Первый пост - featured (большая карточка)
                    $featuredPost = $mediaPosts->first();
                    // Остальные - маленькие карточки
                    $smallMediaPosts = $mediaPosts->slice(1, 4);
                @endphp

                <!-- Main Featured Video/Photo -->
                @if($featuredPost)
                <article class="media-featured">
                    <div class="media-featured-image">
                        @php
                            $featuredMedia = $featuredPost->getMedia('post-gallery')->first();
                            $featuredImageUrl = $featuredMedia ? $featuredMedia->getUrl('medium') : '/images/placeholder.jpg';
                        @endphp
                        <img src="{{ $featuredImageUrl }}" alt="{{ $featuredPost->title }}" loading="lazy">
                        <div class="featured-gradient"></div>

                        @if($featuredPost->types->contains('slug', 'video'))
                        <div class="featured-play">
                            <svg width="80" height="80" viewBox="0 0 80 80">
                                <circle cx="40" cy="40" r="40" fill="white" opacity="0.95"/>
                                <path d="M32 24v32l28-16z" fill="#ef4444"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="media-featured-content">
                        <span class="featured-tag">
                            @if($featuredPost->types->contains('slug', 'video'))
                                VİDEO
                            @else
                                FOTO
                            @endif
                        </span>
                        <h3 class="featured-title">
                            <a href="{{ $featuredPost->url }}">{{ $featuredPost->title }}</a>
                        </h3>
                        @if($featuredPost->author)
                        <div class="featured-author">
                            <img src="{{ $featuredPost->author->avatar_thumb }}" alt="{{ $featuredPost->author->name }}" loading="lazy">
                            <div>
                                <span class="featured-author-name">{{ $featuredPost->author->name }}</span>
                                <span class="featured-date">{{ $featuredPost->published_at->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </article>
                @endif

                <!-- Photos/Videos Grid -->
                @if($smallMediaPosts->isNotEmpty())
                <div class="media-photos">
                    @foreach($smallMediaPosts as $mediaPost)
                    <article class="photo-item">
                        <div class="photo-wrapper">
                            @if($mediaPost->featured_image_thumb)
                                <img src="{{ $mediaPost->featured_image_thumb }}" alt="{{ $mediaPost->title }}" loading="lazy">
                            @else
                                <img src="/images/placeholder.jpg" alt="{{ $mediaPost->title }}" loading="lazy">
                            @endif
                            <div class="photo-gradient"></div>

                            @if($mediaPost->types->contains('slug', 'video'))
                            <div class="photo-icon">
                                <svg width="40" height="40" viewBox="0 0 40 40">
                                    <circle cx="20" cy="20" r="20" fill="white" opacity="0.95"/>
                                    <path d="M16 12v16l14-8z" fill="#ef4444"/>
                                </svg>
                            </div>
                            @else
                            <div class="photo-icon">
                                <svg width="40" height="40" viewBox="0 0 40 40">
                                    <circle cx="20" cy="20" r="20" fill="white" opacity="0.95"/>
                                    <path d="M26 14H14c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V16c0-1.1-.9-2-2-2zm-10 11l-2-2.5 2-2 1.5 1.5 4-5 5 6v3H16z" fill="#ec4899"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="photo-content">
                            <h4 class="photo-title">
                                <a href="{{ $mediaPost->url }}">{{ $mediaPost->title }}</a>
                            </h4>
                            @if($mediaPost->author)
                            <div class="photo-author">
                                <img src="{{ $mediaPost->author->avatar_thumb }}" alt="{{ $mediaPost->author->name }}" loading="lazy">
                                <span class="photo-author-name">{{ $mediaPost->author->name }}</span>
                            </div>
                            @endif
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- All News Section -->
    <section class="section-all-news">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">📰 BÜTÜN XƏBƏRLƏR</h2>
            </div>
            <div class="feed-grid">
                @foreach($latestPosts as $post)
                <article class="feed-card">
                    <div class="feed-image">
                        @if($post->featured_image_thumb)
                            <img src="{{ $post->featured_image_thumb }}" alt="{{ $post->title }}" loading="lazy">
                        @else
                            <img src="/images/placeholder.jpg" alt="{{ $post->title }}" loading="lazy">
                        @endif

                        @if($post->hasMedia('gallery'))
                        <div class="gallery-icon">
                            <svg width="32" height="32" viewBox="0 0 32 32">
                                <rect width="32" height="32" rx="8" fill="url(#galleryGradient)"/>
                                <path d="M24 10H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V12c0-1.1-.9-2-2-2zm-14 12l-2-2.5 2-2 1.5 1.5 4.5-6 5 6.5v3H10z" fill="white"/>
                                <circle cx="11" cy="15" r="1.5" fill="white"/>
                            </svg>
                        </div>
                        @endif

                        @if($post->main_category)
                        <span class="category-badge" data-category-id="{{ $post->main_category->id }}" style="background-color: {{ $post->main_category->color }};">
                            {{ $post->main_category->name }}
                        </span>
                        @endif
                    </div>
                    <div class="feed-content">
                        <h3 class="feed-title">
                            <a href="{{ $post->url }}">{{ $post->title }}</a>
                        </h3>
                        @if($post->author)
                        <div class="news-author">
                            <img src="{{ $post->author->avatar_thumb }}" alt="{{ $post->author->name }}" class="author-avatar" loading="lazy">
                            <div class="author-info">
                                <span class="author-name">{{ $post->author->name }}</span>
                                <span class="publish-date">{{ $post->published_at->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($latestPosts->hasPages())
            <div class="pagination">
                @if($latestPosts->onFirstPage())
                    <button class="pagination-btn pagination-prev" disabled>‹ Əvvəlki</button>
                @else
                    <a href="{{ $latestPosts->previousPageUrl() }}" class="pagination-btn pagination-prev">‹ Əvvəlki</a>
                @endif

                <div class="pagination-numbers">
                    @foreach(range(1, $latestPosts->lastPage()) as $page)
                        @if($page == 1 || $page == $latestPosts->lastPage() || abs($page - $latestPosts->currentPage()) <= 1)
                            @if($page == $latestPosts->currentPage())
                                <button class="pagination-num active">{{ $page }}</button>
                            @else
                                <a href="{{ $latestPosts->url($page) }}" class="pagination-num">{{ $page }}</a>
                            @endif
                        @elseif($page == 2 && $latestPosts->currentPage() > 3)
                            <span class="pagination-dots">...</span>
                        @elseif($page == $latestPosts->lastPage() - 1 && $latestPosts->currentPage() < $latestPosts->lastPage() - 2)
                            <span class="pagination-dots">...</span>
                        @endif
                    @endforeach
                </div>

                @if($latestPosts->hasMorePages())
                    <a href="{{ $latestPosts->nextPageUrl() }}" class="pagination-btn pagination-next">Növbəti ›</a>
                @else
                    <button class="pagination-btn pagination-next" disabled>Növbəti ›</button>
                @endif
            </div>
            @endif
        </div>
    </section>
</main>

<script src="/js/slider.js"></script>
@endsection
