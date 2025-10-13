@extends('layouts.app')

@php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'OLAY.az';
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
    <div class="breadcrumbs">
        <div class="container">
            <a href="{{ route('home') }}" class="breadcrumb-item">Əsas səhifə</a>
            <span class="breadcrumb-separator">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M6 12l4-4-4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="breadcrumb-item active">{{ $category->name }}</span>
        </div>
    </div>

    <!-- Category Hero -->
    <section class="category-hero">
        <div class="category-hero-bg"></div>
        <div class="container">
            <div class="category-hero-content">
                <div class="category-icon-large">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <defs>
                            <linearGradient id="categoryGradient{{ $category->id }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:{{ $category->color }};stop-opacity:0.8" />
                                <stop offset="100%" style="stop-color:{{ $category->color }};stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <circle cx="40" cy="40" r="38" fill="url(#categoryGradient{{ $category->id }})" opacity="0.2"/>
                        <path d="M40 20L45 35H60L48 45L53 60L40 50L27 60L32 45L20 35H35L40 20Z" fill="url(#categoryGradient{{ $category->id }})"/>
                    </svg>
                </div>
                <h1 class="category-title">{{ $category->name }}</h1>
                @if($category->description)
                <p class="category-description">{{ $category->description }}</p>
                @endif
                <div class="category-stats">
                    <span class="category-stat">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H9v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                        {{ $posts->total() }} xəbər
                    </span>
                    <span class="category-stat">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zM9 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L7 13v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H6V8h2c.55 0 1-.45 1-1V5h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                        @if($totalViews >= 1000000)
                            {{ number_format($totalViews / 1000000, 1) }}M baxış
                        @elseif($totalViews >= 1000)
                            {{ number_format($totalViews / 1000, 1) }}K baxış
                        @else
                            {{ number_format($totalViews) }} baxış
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Content -->
    <section class="section-category">
        <div class="container">
            <div class="category-layout">
                <!-- Main Content -->
                <div class="category-main">
                    <!-- Articles Grid -->
                    <div class="category-grid">
                        @forelse($posts as $post)
                        <article class="category-card">
                            <a href="{{ $post->url }}" class="category-card-image">
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

                                <span class="category-badge" data-category-id="{{ $category->id }}" style="background-color: {{ $category->color }};">
                                    {{ $category->name }}
                                </span>
                            </a>
                            <div class="category-card-content">
                                <h3 class="category-card-title">
                                    <a href="{{ $post->url }}">{{ $post->title }}</a>
                                </h3>
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
                        @if($posts->onFirstPage())
                            <button class="pagination-btn pagination-prev" disabled>‹ Əvvəlki</button>
                        @else
                            <a href="{{ $posts->previousPageUrl() }}" class="pagination-btn pagination-prev">‹ Əvvəlki</a>
                        @endif

                        <div class="pagination-numbers">
                            @foreach(range(1, $posts->lastPage()) as $page)
                                @if($page == 1 || $page == $posts->lastPage() || abs($page - $posts->currentPage()) <= 1)
                                    @if($page == $posts->currentPage())
                                        <button class="pagination-num active">{{ $page }}</button>
                                    @else
                                        <a href="{{ $posts->url($page) }}" class="pagination-num">{{ $page }}</a>
                                    @endif
                                @elseif($page == 2 && $posts->currentPage() > 3)
                                    <span class="pagination-dots">...</span>
                                @elseif($page == $posts->lastPage() - 1 && $posts->currentPage() < $posts->lastPage() - 2)
                                    <span class="pagination-dots">...</span>
                                @endif
                            @endforeach
                        </div>

                        @if($posts->hasMorePages())
                            <a href="{{ $posts->nextPageUrl() }}" class="pagination-btn pagination-next">Növbəti ›</a>
                        @else
                            <button class="pagination-btn pagination-next" disabled>Növbəti ›</button>
                        @endif
                    </div>
                    @endif
                </div>

                @include('partials.sidebar')
            </div>
        </div>
    </section>
@endsection
