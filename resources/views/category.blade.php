@extends('layouts.app')

@php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'News24.az';
@endphp

@section('title', $category->name . ' - ' . $siteName)

@section('seo')
    @php
        $categoryTitle = $category->name . ' xəbərləri - ' . $siteName;
        $categoryDescription = $category->description ?: $category->name . ' kateqoriyasından ən son və aktual xəbərlər. ' . $siteName . ' - Azərbaycanın aparıcı xəbər portalı.';
        $categoryKeywords = $category->name . ', ' . $category->name . ' xəbərləri, azərbaycan ' . strtolower($category->name) . ', son xəbərlər, ' . strtolower($siteName);
    @endphp
    <x-seo
        :title="$categoryTitle"
        :description="$categoryDescription"
        :keywords="$categoryKeywords"
        :ogType="'website'"
        :ogTitle="$category->name . ' - Son xəbərlər | ' . $siteName"
        :ogDescription="$categoryDescription"
        :ogImage="$category->image ? $category->image : asset('images/logo-cropped.png')"
        :ogUrl="route('category', $category->slug)"
        :canonical="route('category', $category->slug)"
        :twitterCard="'summary_large_image'"
        :twitterTitle="$category->name . ' xəbərləri'"
        :twitterDescription="$category->name . ' kateqoriyasından ən son xəbərlər'"
        :section="$category->name"
    />
@endsection

@section('schema')
    {{-- Category CollectionPage Schema --}}
    <x-schema
        type="category"
        :category="$category"
    />

    {{-- BreadcrumbList Schema --}}
    <x-schema
        type="breadcrumb"
        :breadcrumbs="[
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            ['name' => $category->name, 'url' => route('category', $category->slug)]
        ]"
    />

    {{-- CollectionPage Schema with Category Posts --}}
    @if($posts->isNotEmpty())
    <x-schema
        type="collectionpage"
        :posts="$posts"
        :pageTitle="$category->name . ' - Son xəbərlər'"
        :pageDescription="$category->name . ' kateqoriyasından ən son və aktual xəbərlər'"
    />
    @endif

    {{-- ItemList Schema --}}
    @if($posts->isNotEmpty())
    <x-schema
        type="itemlist"
        :items="$posts"
    />
    @endif
@endsection

@section('content')
    <!-- Breadcrumbs -->
    <section class="breadcrumbs-section">
        <div class="container">
            <div class="breadcrumbs">
                <a href="{{ route('home') }}" class="breadcrumb-item">Ana səhifə</a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-item active">{{ $category->name }}</span>
            </div>
        </div>
    </section>

    <!-- Top Banner Ad Section -->
    <section class="top-banner-ad-section" style="padding: 20px 0;">
        <div class="container">
            @php
                $topBannerAd = config_value('TOP_BANNER_AD_DESKTOP', '/images/ad-banner-1080x160.svg');
                $topBannerAdLink = config_value('TOP_BANNER_AD_LINK_DESKTOP', '#');
                $mobileBanner = config_value('TOP_BANNER_MOBILE', '/images/ad-banner-430x200.svg');
                $mobileBannerLink = config_value('TOP_BANNER_MOBILE_LINK', '#');
            @endphp
            <!-- Desktop Banner -->
            <div class="top-banner-desktop" style="display: block; max-width: 1080px; margin: 0 auto;">
                @if(str_starts_with(trim($topBannerAd), '<'))
                    {!! $topBannerAd !!}
                @else
                    <a href="{{ $topBannerAdLink }}" target="_blank" rel="noopener">
                        <img src="{{ $topBannerAd }}" alt="Reklam" width="1080" height="160" style="width: 100%; height: auto; border-radius: 8px; display: block;" loading="lazy">
                    </a>
                @endif
            </div>
            <!-- Mobile Banner -->
            <div class="top-banner-mobile" style="display: none; max-width: 430px; margin: 0 auto;">
                @if(str_starts_with(trim($mobileBanner), '<'))
                    {!! $mobileBanner !!}
                @else
                    <a href="{{ $mobileBannerLink }}" target="_blank" rel="noopener">
                        <img src="{{ $mobileBanner }}" alt="Reklam" width="430" height="200" style="width: 100%; height: auto; border-radius: 8px; display: block;" loading="lazy">
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Category Header -->
    <section class="category-header-section">
        <div class="container">
            <div class="category-header">
                <div class="category-header-content">
                    <span class="category-badge category-{{ $category->id }} large">{{ $category->name }}</span>
                    <h1 class="category-title">{{ $category->name }} xəbərləri</h1>
                    @if($category->description)
                    <p class="category-description">{{ $category->description }}</p>
                    @else
                    <p class="category-description">Azərbaycan və dünya {{ mb_strtolower($category->name) }} haqqında ən son xəbərlər və təhlillər</p>
                    @endif
                </div>
                <div class="category-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $posts->total() }}</span>
                        <span class="stat-label">Xəbər</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $todayPostsCount }}</span>
                        <span class="stat-label">Bu gün</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Grid -->
    <section class="news-grid-section">
        <div class="container">
            <div class="grid-layout">
                <!-- Main Column -->
                <div class="main-column">
                    <!-- Regular News Cards -->
                    <div class="news-cards-grid">
                        @forelse($posts as $post)
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
                        @empty
                        <div class="no-posts">
                            <p>Bu kateqoriyada hələ xəbər yoxdur.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($posts->hasPages())
                    <div class="pagination">
                        @if(!$posts->onFirstPage())
                        <a href="{{ $posts->previousPageUrl() }}" class="pagination-btn pagination-prev">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M12 15l-5-5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </a>
                        @else
                        <button class="pagination-btn pagination-prev" disabled>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M12 15l-5-5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                        @endif

                        @foreach(range(1, min($posts->lastPage(), 5)) as $page)
                            @if($page == $posts->currentPage())
                                <button class="pagination-btn pagination-page active">{{ $page }}</button>
                            @else
                                <a href="{{ $posts->url($page) }}" class="pagination-btn pagination-page">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}" class="pagination-btn pagination-next">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M8 15l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </a>
                        @else
                        <button class="pagination-btn pagination-next" disabled>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M8 15l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
