@extends('layouts.app')

@php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'News24.az';
@endphp

@section('title', $author->name . ' - Müəllif | ' . $siteName)

@section('seo')
    <x-seo
        :title="$author->name . ' - Müəllif | ' . $siteName"
        :description="$author->bio ?: $author->name . ' tərəfindən yazılmış xəbərlər və məqalələr. ' . $stats['total_posts'] . ' məqalə, ' . number_format($stats['total_views']) . ' baxış.'"
        :keywords="$author->name . ', müəllif, jurnalist, ' . $siteName"
        :ogType="'profile'"
        :ogTitle="$author->name . ' - Müəllif'"
        :ogDescription="$author->bio ?: $author->name . ' tərəfindən yazılmış xəbərlər'"
        :ogImage="$author->avatar_thumb"
        :ogUrl="route('author.show', $author->slug)"
        :canonical="route('author.show', $author->slug)"
    />

    <style>
        @media (max-width: 768px) {
            .author-profile-section {
                padding: 30px 0 !important;
            }
            .author-profile-card {
                padding: 30px 20px !important;
            }
            .author-name {
                font-size: 24px !important;
            }
            .author-stats {
                gap: 24px !important;
            }
            .stat-item div:first-child {
                font-size: 24px !important;
            }
        }
    </style>
@endsection

@section('schema')
    {{-- Person Schema (Enhanced for E-A-T) --}}
    @php
    $personSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $author->name,
        'url' => route('author.show', $author->slug),
        'jobTitle' => 'Jurnalist',
        'worksFor' => [
            '@type' => 'NewsMediaOrganization',
            'name' => 'News24.az',
            'url' => config('app.url')
        ],
        'sameAs' => []
    ];
    
    if ($author->avatar_thumb) {
        $personSchema['image'] = [
            '@type' => 'ImageObject',
            'url' => $author->avatar_thumb
        ];
    }
    
    if ($author->bio) {
        $personSchema['description'] = $author->bio;
    }
    
    if ($author->email) {
        $personSchema['email'] = $author->email;
    }
    
    // Add expertise areas based on categories
    $authorCategories = \App\Models\Post::published()
        ->where('author_id', $author->id)
        ->with('categories')
        ->get()
        ->map(fn($p) => $p->main_category?->name)->filter()
        ->filter()
        ->unique()
        ->take(5)
        ->toArray();
    
    if (!empty($authorCategories)) {
        $personSchema['knowsAbout'] = $authorCategories;
    }
    
    // Add article count and interaction stats
    $personSchema['numberOfItems'] = $stats['total_posts'];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($personSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    {{-- BreadcrumbList Schema --}}
    <x-schema
        type="breadcrumb"
        :breadcrumbs="[
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            ['name' => $author->name, 'url' => route('author.show', $author->slug)]
        ]"
    />

    {{-- ItemList Schema for Author Posts --}}
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
                <span class="breadcrumb-item active">{{ $author->name }}</span>
            </div>
        </div>
    </section>

    <!-- Author Profile Section -->
    <section class="author-profile-section" style="padding: 40px 0; margin-bottom: 40px;">
        <div class="container">
            <div class="author-profile-card" style="background: white; border-radius: 20px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 20px;">
                <div class="author-avatar-large" style="position: relative;">
                    <img src="{{ $author->avatar_thumb }}" alt="{{ $author->name }}" width="150" height="150" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                </div>
                <div class="author-info" style="max-width: 600px;">
                    <h1 class="author-name" style="font-size: 32px; font-weight: 700; color: #1a202c; margin-bottom: 8px;">{{ $author->name }}</h1>
                    <p class="author-role" style="font-size: 16px; color: #667eea; font-weight: 600; margin-bottom: 16px;">Jurnalist</p>
                    @if($author->bio)
                    <p class="author-bio" style="font-size: 16px; line-height: 1.6; color: #4a5568; margin-bottom: 24px;">{{ $author->bio }}</p>
                    @endif
                    <div class="author-stats" style="display: flex; gap: 40px; justify-content: center; flex-wrap: wrap; margin-top: 24px;">
                        <div class="stat-item" style="text-align: center;">
                            <div style="font-size: 28px; font-weight: 700; color: #667eea; margin-bottom: 4px;">{{ $stats['total_posts'] }}</div>
                            <div style="font-size: 14px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Məqalə</div>
                        </div>
                        <div class="stat-item" style="text-align: center;">
                            <div style="font-size: 28px; font-weight: 700; color: #667eea; margin-bottom: 4px;">{{ number_format($stats['total_views']) }}</div>
                            <div style="font-size: 14px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Baxış</div>
                        </div>
                        <div class="stat-item" style="text-align: center;">
                            <div style="font-size: 28px; font-weight: 700; color: #667eea; margin-bottom: 4px;">{{ $stats['recent_posts'] }}</div>
                            <div style="font-size: 14px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Son 30 gün</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Author Posts -->
    <section class="author-posts-section">
        <div class="container">
            <h2 class="section-title">{{ $author->name }} tərəfindən yazılmış məqalələr</h2>
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
                <div class="no-posts" style="text-align: center; padding: 60px 20px; background: #f7fafc; border-radius: 12px; margin: 40px 0;">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none" style="margin: 0 auto 20px;">
                        <circle cx="40" cy="40" r="40" fill="#e2e8f0"/>
                        <path d="M30 35h20M30 45h15" stroke="#718096" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <p style="font-size: 18px; color: #4a5568; margin: 0;">Bu müəllifin hələ məqaləsi yoxdur.</p>
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
    </section>
@endsection

