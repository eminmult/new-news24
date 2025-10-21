@extends('layouts.app')

@php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'News24.az';
@endphp

@section('title', $category->name . ' - ' . $siteName)

@section('seo')
    <x-seo
        :title="$category->name . ' - ' . $siteName"
        :description="$category->description ?: $category->name . ' kateqoriyasından ən son xəbərlər. ' . $siteName . ' - Azərbaycanın aparıcı xəbər portalı.'"
        :keywords="$category->name . ', ' . $category->name . ' xəbərləri, azərbaycan xəbərləri, son xəbərlər, ' . strtolower($siteName)"
        :ogType="'website'"
        :ogImage="asset('images/logo-cropped.png')"
        :canonical="route('category', $category->slug)"
    />
@endsection

@section('schema')
    {{-- BreadcrumbList Schema --}}
    <x-schema
        type="breadcrumb"
        :breadcrumbs="[
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            ['name' => $category->name, 'url' => route('category', $category->slug)]
        ]"
    />

    {{-- ItemList Schema (список постов в категории) --}}
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
                        <span class="stat-number">{{ $posts->where('published_at', '>=', now()->startOfDay())->count() }}</span>
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
