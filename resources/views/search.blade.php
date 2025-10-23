@extends('layouts.app')

@php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'News24.az';
@endphp

@section('title', 'Axtarış nəticələri: ' . $query . ' - ' . $siteName)

@section('seo')
    @php
        $searchTitle = "Axtarış: {$query} - {$siteName}";
        $searchDescription = "\"{$query}\" üzrə axtarış nəticələri {$siteName}-da. {$posts->total()} nəticə tapıldı.";
        $searchKeywords = "{$query}, axtarış, xəbərlər, " . strtolower($siteName);
    @endphp
    <x-seo
        :title="$searchTitle"
        :description="$searchDescription"
        :keywords="$searchKeywords"
        :ogType="'website'"
        :ogImage="asset('images/logo-cropped.png')"
        :canonical="route('search', ['q' => $query])"
        :robots="'noindex, follow'"
    />
@endsection

@section('schema')
    {{-- BreadcrumbList Schema --}}
    <x-schema
        type="breadcrumb"
        :breadcrumbs="[
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            ['name' => 'Axtarış', 'url' => route('search', ['q' => $query])]
        ]"
    />

    {{-- SearchResultsPage Schema --}}
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "SearchResultsPage",
      "name": "Axtarış nəticələri: {{ $query }}",
      "description": "{{ $posts->total() }} nəticə tapıldı",
      "url": "{{ route('search', ['q' => $query]) }}",
      "inLanguage": "az",
      "potentialAction": {
        "@@type": "SearchAction",
        "target": {
          "@@type": "EntryPoint",
          "urlTemplate": "{{ config('app.url') }}/search?q={search_term_string}"
        },
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    {{-- ItemList Schema (если есть результаты) --}}
    @if($posts->isNotEmpty())
    <x-schema
        type="itemlist"
        :items="$posts"
    />
    @endif
@endsection

@section('content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs-section">
        <div class="container">
            <div class="breadcrumbs">
                <a href="{{ route('home') }}" class="breadcrumb-item">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M8 1l7 7-1.5 1.5L8 4l-5.5 5.5L1 8l7-7z"/>
                        <path d="M3 8v7h4v-4h2v4h4V8"/>
                    </svg>
                    Əsas səhifə
                </a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-item active">Axtarış</span>
            </div>
        </div>
    </div>

    <!-- Search Header -->
    <section class="search-header">
        <div class="container">
            <div class="search-header-content">
                <div class="search-title-section">
                    <h1 class="search-page-title">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35" stroke-linecap="round"/>
                        </svg>
                        Axtarış nəticələri
                    </h1>
                    <p class="search-subtitle">
                        "<span class="search-query-highlight">{{ $query }}</span>" üzrə
                        <strong>{{ $posts->total() }}</strong> nəticə tapıldı
                    </p>
                </div>

                <!-- Search Form -->
                <form class="search-form-inline" action="{{ route('search') }}" method="GET">
                    <div class="search-input-group">
                        <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35" stroke-linecap="round"/>
                        </svg>
                        <input
                            type="text"
                            name="q"
                            class="search-input-field"
                            placeholder="Yeni axtarış..."
                            value="{{ $query }}"
                            autocomplete="off"
                            required
                        >
                        <button type="submit" class="search-submit-button">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35" stroke-linecap="round"/>
                            </svg>
                            Axtar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Search Results -->
    <section class="search-results-section">
        <div class="container">
            @forelse($posts as $post)
                @if($loop->first)
                <div class="news-cards-grid">
                @endif

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

                @if($loop->last)
                </div>
                @endif
            @empty
                <div class="no-results-wrapper">
                    <div class="no-results-card">
                        <div class="no-results-icon">
                            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35" stroke-linecap="round"/>
                                <line x1="8" y1="11" x2="14" y2="11" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <h2 class="no-results-title">Heç bir nəticə tapılmadı</h2>
                        <p class="no-results-text">
                            "<strong>{{ $query }}</strong>" üzrə axtarış nəticəsi tapılmadı.<br>
                            Zəhmət olmasa başqa açar söz ilə yenidən cəhd edin.
                        </p>
                        <div class="no-results-suggestions">
                            <p class="suggestions-title">Təkliflər:</p>
                            <ul class="suggestions-list">
                                <li>Daha ümumi terminlərdən istifadə edin</li>
                                <li>Yazılışı yoxlayın</li>
                                <li>Daha az söz istifadə edin</li>
                                <li>Müxtəlif sinonimləri sınayın</li>
                            </ul>
                        </div>
                        <a href="{{ route('home') }}" class="back-home-btn">
                            <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 1l7 7-1.5 1.5L8 4l-5.5 5.5L1 8l7-7z"/>
                                <path d="M3 8v7h4v-4h2v4h4V8"/>
                            </svg>
                            Ana səhifəyə qayıt
                        </a>
                    </div>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($posts->hasPages())
            <div class="pagination">
                @if($posts->onFirstPage())
                    <button class="pagination-btn pagination-prev" disabled>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                @else
                    <a href="{{ $posts->appends(['q' => $query])->previousPageUrl() }}" class="pagination-btn pagination-prev">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                @endif

                <div class="pagination-numbers">
                    @foreach(range(1, $posts->lastPage()) as $page)
                        @if($page == 1 || $page == $posts->lastPage() || abs($page - $posts->currentPage()) <= 1)
                            @if($page == $posts->currentPage())
                                <button class="pagination-num active">{{ $page }}</button>
                            @else
                                <a href="{{ $posts->appends(['q' => $query])->url($page) }}" class="pagination-num">{{ $page }}</a>
                            @endif
                        @elseif($page == 2 && $posts->currentPage() > 3)
                            <span class="pagination-dots">...</span>
                        @elseif($page == $posts->lastPage() - 1 && $posts->currentPage() < $posts->lastPage() - 2)
                            <span class="pagination-dots">...</span>
                        @endif
                    @endforeach
                </div>

                @if($posts->hasMorePages())
                    <a href="{{ $posts->appends(['q' => $query])->nextPageUrl() }}" class="pagination-btn pagination-next">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                @else
                    <button class="pagination-btn pagination-next" disabled>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                @endif
            </div>
            @endif
        </div>
    </section>
@endsection
