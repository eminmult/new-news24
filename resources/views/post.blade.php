@extends('layouts.app')

@php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'News24.az';
@endphp

@section('title', $post->meta_title . ' - ' . $siteName)

@section('seo')
    <x-seo
        :title="$post->meta_title"
        :description="$post->meta_description"
        :keywords="$post->meta_keywords"
        :ogType="'article'"
        :ogTitle="$post->title"
        :ogDescription="$post->meta_description"
        :ogImage="$post->featured_image"
        :canonical="$post->url"
        :publishedTime="$post->published_at->toIso8601String()"
        :modifiedTime="$post->updated_at->toIso8601String()"
        :section="$post->main_category?->name"
        :tags="$post->tags->pluck('name')->toArray()"
        :twitterSite="'@news24_az'"
    />
@endsection

@section('schema')
    {{-- BreadcrumbList Schema --}}
    <x-schema
        type="breadcrumb"
        :breadcrumbs="array_filter([
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            $post->main_category ? ['name' => $post->main_category->name, 'url' => route('category', $post->main_category->slug)] : null,
            ['name' => $post->title, 'url' => $post->url]
        ])"
    />

    {{-- NewsArticle Schema --}}
    <x-schema
        type="newsarticle"
        :article="$post"
    />

    {{-- ImageGallery Schema (если есть галерея) --}}
    @if($post->hasMedia('post-gallery') && $post->getMedia('post-gallery')->count() > 1)
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "ImageGallery",
      "name": "{{ $post->title }} - Фотогалерея",
      "description": "{{ $post->meta_description }}",
      "image": [
        @foreach($post->getMedia('post-gallery') as $media)
        {
          "@@type": "ImageObject",
          "url": "{{ $media->getFullUrl('webp') }}",
          "width": 1200,
          "height": 800,
          "caption": "{{ $post->title }}"
        }@if(!$loop->last),@endif
        @endforeach
      ]
    }
    </script>
    @endif

    {{-- VideoObject Schema (для YouTube/OK.ru видео) --}}
    @foreach($post->widgets->whereIn('type', ['youtube', 'okru']) as $videoWidget)
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "VideoObject",
      "name": "{{ $post->title }}",
      "description": "{{ $post->meta_description }}",
      "thumbnailUrl": "{{ $post->featured_image }}",
      "uploadDate": "{{ $post->published_at->toIso8601String() }}",
      @if($videoWidget->type === 'youtube')
      "embedUrl": "https://www.youtube.com/embed/{{ $videoWidget->content }}",
      "contentUrl": "https://www.youtube.com/watch?v={{ $videoWidget->content }}"
      @else
      "embedUrl": "https://ok.ru/videoembed/{{ $videoWidget->content }}"
      @endif
    }
    </script>
    @endforeach
@endsection

@section('content')
    <!-- Breadcrumbs -->
    <section class="breadcrumbs-section">
        <div class="container">
            <div class="breadcrumbs">
                <a href="{{ route('home') }}" class="breadcrumb-item">Ana səhifə</a>
                @if($post->main_category)
                <span class="breadcrumb-separator">›</span>
                <a href="{{ route('category', $post->main_category->slug) }}" class="breadcrumb-item">{{ $post->main_category->name }}</a>
                @endif
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-item active">{{ $post->title }}</span>
            </div>
        </div>
    </section>

    <!-- Article Section -->
    <section class="article-section">
        <div class="container">
            <div class="article-layout">
                <!-- Main Article -->
                <article class="article-main">
                    <div class="article-header">
                        <h1 class="article-title">{{ $post->title }}</h1>
                    </div>

                    <div class="article-image">
                        @if($post->featured_image_large)
                        <img src="{{ $post->featured_image_large }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px;">
                        @endif
                        @if($post->main_category)
                        <span class="category-badge category-{{ $post->main_category->id }}">{{ $post->main_category->name }}</span>
                        @endif
                    </div>

                    <div class="article-meta">
                        <div class="article-author">
                            @if($post->author)
                            <div class="author-avatar">
                                <img src="{{ $post->author->avatar_thumb }}" alt="{{ $post->author->name }}">
                            </div>
                            <div class="author-info">
                                <span class="author-name">{{ $post->author->name }}</span>
                                <span class="publish-date">{{ format_date_az($post->published_at, 'd F Y, H:i') }}</span>
                            </div>
                            @else
                            <div class="author-info">
                                <span class="publish-date">{{ format_date_az($post->published_at, 'd F Y, H:i') }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="article-stats">
                            <span class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                    <path d="M8 2C4.5 2 1.5 4.5 1 8c.5 3.5 3.5 6 7 6s6.5-2.5 7-6c-.5-3.5-3.5-6-7-6zm0 10c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4z"/>
                                    <circle cx="8" cy="8" r="2"/>
                                </svg>
                                <span class="views-count">{{ $post->views >= 1000 ? number_format($post->views / 1000, 1) . 'K' : $post->views }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="article-content">
                        {!! $post->content !!}

                        <!-- Article Gallery -->
                        @if($post->hasMedia('post-gallery') && $post->getMedia('post-gallery')->count() > 1)
                        <div class="article-gallery" style="display: flex; flex-direction: column; align-items: center; gap: 20px; margin-top: 32px;">
                            @foreach($post->getMedia('post-gallery')->skip(1) as $media)
                            <img src="{{ $media->getUrl('webp') }}" alt="{{ $post->title }}" style="width: 100%; height: auto; border-radius: 20px;" loading="lazy">
                            @endforeach
                        </div>
                        @endif

                        <!-- Article Widgets -->
                        @if($post->widgets->isNotEmpty())
                            @foreach($post->widgets as $widget)
                                @if($widget->type === 'youtube')
                                <div class="article-video" style="margin: 32px 0;">
                                    <div class="video-wrapper" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 20px;">
                                        <iframe src="https://www.youtube.com/embed/{{ $widget->content }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                                    </div>
                                </div>
                                @elseif($widget->type === 'okru')
                                <div class="article-video" style="margin: 32px 0;">
                                    <div class="video-wrapper" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 20px;">
                                        <iframe src="https://ok.ru/videoembed/{{ $widget->content }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allow="autoplay" allowfullscreen></iframe>
                                    </div>
                                </div>
                                @elseif($widget->type === 'instagram')
                                <div class="widget-item widget-instagram" style="margin: 32px 0;">
                                    <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/{{ $widget->content }}/" data-instgrm-version="14"></blockquote>
                                </div>
                                @else
                                <div class="widget-item widget-embed" style="margin: 32px 0;">
                                    {!! $widget->content !!}
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="article-footer">
                        @if($post->tags->isNotEmpty())
                        <div class="article-tags">
                            <span class="tag-label">Teqlər:</span>
                            @foreach($post->tags as $tag)
                            <a href="{{ route('search', ['q' => $tag->name]) }}" class="article-tag">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                        @endif

                        <div class="article-share">
                            <span class="share-label">Paylaş:</span>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($post->url) }}" target="_blank" class="share-btn facebook">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode($post->url) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn twitter">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . $post->url) }}" target="_blank" class="share-btn whatsapp">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                </a>
                                <a href="https://t.me/share/url?url={{ urlencode($post->url) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn telegram">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Sidebar -->
                <aside class="article-sidebar">
                    <!-- Popular News Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Populyar xəbərlər</h3>
                        <div class="popular-list">
                            @php
                                $popularNewsCount = (int) \App\Models\Setting::get('popular_posts_count', 5);
                                $popularNewsDays = (int) \App\Models\Setting::get('popular_posts_days', 7);
                                $popularPosts = \App\Models\Post::published()
                                    ->where('published_at', '>=', now()->subDays($popularNewsDays))
                                    ->orderBy('views', 'desc')
                                    ->limit($popularNewsCount)
                                    ->get();
                            @endphp
                            @foreach($popularPosts as $index => $popularPost)
                            <a href="{{ $popularPost->url }}" class="popular-item">
                                <div class="popular-number">{{ $index + 1 }}</div>
                                <div class="popular-content">
                                    <h4>{{ $popularPost->title }}</h4>
                                    <span class="popular-views">
                                        @if($popularPost->views >= 1000)
                                            {{ number_format($popularPost->views / 1000, 1) }}K baxış
                                        @else
                                            {{ $popularPost->views }} baxış
                                        @endif
                                    </span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Video Widget -->
                    @php
                        $videoPost = \App\Models\Post::published()
                            ->whereHas('widgets', function($q) {
                                $q->whereIn('type', ['youtube', 'okru']);
                            })
                            ->with('widgets')
                            ->orderBy('published_at', 'desc')
                            ->first();
                    @endphp
                    @if($videoPost && $videoPost->widgets->whereIn('type', ['youtube', 'okru'])->first())
                    <div class="sidebar-widget video-widget">
                        <h3 class="widget-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                            </svg>
                            Videoxəbər
                        </h3>
                        <div class="video-news-card">
                            <div class="video-thumbnail">
                                @if($videoPost->featured_image_medium)
                                    <div class="video-thumbnail-bg" style="background-image: url('{{ $videoPost->featured_image_medium }}'); background-size: cover; background-position: center;"></div>
                                @else
                                    <div class="video-thumbnail-bg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                @endif
                                <a href="{{ $videoPost->url }}" class="video-play-btn">
                                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                        <circle cx="24" cy="24" r="24" fill="white" opacity="0.95"/>
                                        <path d="M20 15l13 9-13 9V15z" fill="#ef4444"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="video-info">
                                <h4 class="video-title">{{ $videoPost->title }}</h4>
                                <p class="video-meta">
                                    <span>{{ $videoPost->views >= 1000 ? number_format($videoPost->views / 1000, 1) . 'K' : $videoPost->views }} baxış</span>
                                    <span>{{ $videoPost->published_at->diffForHumans() }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Weather Widget -->
                    @php
                        try {
                            $weatherCity = \App\Models\Setting::get('weather_city', 'Baku');
                            $weatherService = app(\App\Services\WeatherService::class);
                            $weather = $weatherService->getWeather($weatherCity);
                            $showWeather = true;
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Weather widget error: ' . $e->getMessage());
                            // Fallback данные на случай ошибки
                            $weather = [
                                'temperature' => 24,
                                'humidity' => 65,
                                'wind_speed' => 12,
                                'icon' => '☀️',
                                'description' => 'Açıq hava',
                            ];
                            $showWeather = true;
                        }
                    @endphp
                    @if($showWeather)
                    <div class="sidebar-widget weather-widget">
                        <h3 class="widget-title">Bakı</h3>
                        <div class="weather-content">
                            <div class="weather-main">
                                <div class="weather-icon">{{ $weather['icon'] }}</div>
                                <div class="weather-temp">{{ $weather['temperature'] }}°</div>
                            </div>
                            <div class="weather-details">
                                <div class="weather-detail">
                                    <span>Nəmlik</span>
                                    <strong>{{ $weather['humidity'] }}%</strong>
                                </div>
                                <div class="weather-detail">
                                    <span>Külək</span>
                                    <strong>{{ $weather['wind_speed'] }} km/s</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    <!-- Related News -->
    @if($relatedPosts->isNotEmpty())
    <section class="related-news-section">
        <div class="container">
            <h2 class="section-title">Son xəbərlər</h2>
            <div class="news-cards-grid">
                @foreach($relatedPosts->take(3) as $relatedPost)
                <article class="news-card">
                    <a href="{{ $relatedPost->url }}">
                        <div class="card-image">
                            @if($relatedPost->featured_image_thumb)
                                <img src="{{ $relatedPost->featured_image_thumb }}" alt="{{ $relatedPost->title }}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                            @else
                                <div class="img-gradient-{{ ($loop->index % 8) + 1 }}" style="width: 100%; height: 100%;"></div>
                            @endif
                            <span class="news-card-date">
                                @if($relatedPost->published_at->isToday())
                                    {{ $relatedPost->published_at->format('H:i') }}
                                @elseif($relatedPost->published_at->year == now()->year)
                                    {{ format_date_az($relatedPost->published_at, 'd M H:i') }}
                                @else
                                    {{ format_date_az($relatedPost->published_at, 'd M H:i, Y') }}
                                @endif
                            </span>
                        </div>

                        @if($relatedPost->main_category)
                        <span class="category-badge category-{{ $relatedPost->main_category->id }}">
                            {{ $relatedPost->main_category->name }}
                        </span>
                        @endif

                        <div class="card-content">
                            <h3 class="card-title">{{ $relatedPost->title }}</h3>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection

@section('scripts')
    <!-- Instagram Embed Script -->
    @if($post->widgets->where('type', 'instagram')->isNotEmpty())
    <script async src="https://www.instagram.com/embed.js"></script>
    @endif

    <!-- Twitter/X Embed Script -->
    @if($post->widgets->where('type', 'x')->isNotEmpty())
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    @endif

    <!-- Facebook SDK -->
    @if($post->widgets->where('type', 'fbvideo')->isNotEmpty())
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0"></script>
    @endif

    <!-- Telegram Widget Script -->
    @if($post->widgets->where('type', 'telegram')->isNotEmpty())
    <script async src="https://telegram.org/js/telegram-widget.js?22"></script>
    @endif
@endsection
