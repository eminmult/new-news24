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
    <div class="breadcrumbs">
        <div class="container">
            <a href="{{ route('home') }}" class="breadcrumb-item">Əsas səhifə</a>
            @if($post->main_category)
            <span class="breadcrumb-separator">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M6 12l4-4-4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <a href="{{ route('category', $post->main_category->slug) }}" class="breadcrumb-item">{{ $post->main_category->name }}</a>
            @endif
            <span class="breadcrumb-separator">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M6 12l4-4-4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="breadcrumb-item active">{{ $post->title }}</span>
        </div>
    </div>

    <!-- Article Content -->
    <section class="section-article">
        <div class="container">
            <div class="article-layout">
                <!-- Main Article -->
                <article class="article-main">
                    <!-- Article Header -->
                    <header class="article-header">
                        @if($post->main_category)
                        <span class="category-badge" data-category-id="{{ $post->main_category->id }}" style="background-color: {{ $post->main_category->color }};">
                            {{ $post->main_category->name }}
                        </span>
                        @endif
                        <h1 class="article-title">{{ $post->title }}</h1>

                        <div class="article-meta">
                            @if($post->author)
                            <div class="news-author">
                                <img src="{{ $post->author->avatar_thumb }}" alt="{{ $post->author->name }}" class="author-avatar" loading="lazy">
                                <div class="author-info">
                                    <span class="author-name">{{ $post->author->name }}</span>
                                    <span class="publish-date">{{ $post->published_at->translatedFormat('d F Y, H:i') }}</span>
                                </div>
                            </div>
                            @endif
                            <div class="article-stats">
                                @if($post->read_time)
                                <span class="article-stat">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="currentColor">
                                        <path d="M9 0C4.05 0 0 4.05 0 9s4.05 9 9 9 9-4.05 9-9-4.05-9-9-9zm0 16.2c-3.96 0-7.2-3.24-7.2-7.2S5.04 1.8 9 1.8s7.2 3.24 7.2 7.2-3.24 7.2-7.2 7.2z"/>
                                        <path d="M9.45 4.5h-1.8v5.4l4.725 2.835.9-1.485L9.45 9.45V4.5z"/>
                                    </svg>
                                    {{ $post->read_time }} dəq
                                </span>
                                @endif
                                <span class="article-stat">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="currentColor">
                                        <path d="M9 3C4.5 3 .73 5.61 0 9c.73 3.39 4.5 6 9 6s8.27-2.61 9-6c-.73-3.39-4.5-6-9-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm0-6.4c-1.32 0-2.4 1.08-2.4 2.4s1.08 2.4 2.4 2.4 2.4-1.08 2.4-2.4-1.08-2.4-2.4-2.4z"/>
                                    </svg>
                                    @if($post->views >= 1000)
                                        {{ number_format($post->views / 1000, 1) }}K
                                    @else
                                        {{ $post->views }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </header>

                    <!-- Article Image -->
                    @if($post->featured_image_webp)
                    <figure class="article-featured-image">
                        <img src="{{ $post->featured_image_webp }}" alt="{{ $post->title }}">
                    </figure>
                    @endif

                    <!-- Article Body -->
                    <div class="article-body">
                        {!! $post->content !!}

                        <!-- Article Gallery -->
                        @if($post->hasMedia('post-gallery') && $post->getMedia('post-gallery')->count() > 1)
                        <div class="article-gallery" style="display: flex; flex-direction: column; align-items: center; gap: 20px; margin-top: 32px;">
                            @foreach($post->getMedia('post-gallery')->skip(1) as $media)
                            <img src="{{ $media->getUrl('webp') }}" alt="{{ $post->title }}" style="width: 100%; height: auto; border-radius: 8px;" loading="lazy">
                            @endforeach
                        </div>
                        @endif

                        <!-- Article Widgets -->
                        @if($post->widgets->isNotEmpty())
                            @foreach($post->widgets as $widget)
                                @if($widget->type === 'youtube')
                                <div class="article-video">
                                    <div class="video-wrapper">
                                        <iframe src="https://www.youtube.com/embed/{{ $widget->content }}" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                                    </div>
                                </div>
                                @elseif($widget->type === 'okru')
                                <div class="article-video">
                                    <div class="video-wrapper">
                                        <iframe src="https://ok.ru/videoembed/{{ $widget->content }}" allow="autoplay" allowfullscreen></iframe>
                                    </div>
                                </div>
                                @elseif($widget->type === 'instagram')
                                <div class="widget-item widget-instagram">
                                    <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/{{ $widget->content }}/" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"></blockquote>
                                </div>
                                @else
                                <div class="widget-item widget-embed">
                                    {!! $widget->content !!}
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <!-- Article Tags -->
                    @if($post->tags->isNotEmpty())
                    <div class="article-tags">
                        <span class="tag-label">Teqlər:</span>
                        @foreach($post->tags as $tag)
                        <a href="{{ route('search', ['q' => $tag->name]) }}" class="article-tag">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                    @endif

                    <!-- Article Share -->
                    <div class="article-share">
                        <span class="share-label">Paylaş:</span>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($post->url) }}" target="_blank" class="share-btn share-facebook">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode($post->url) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn share-twitter">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . $post->url) }}" target="_blank" class="share-btn share-whatsapp">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </a>
                            <a href="https://t.me/share/url?url={{ urlencode($post->url) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn share-telegram">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Related Articles -->
                    @if($relatedPosts->isNotEmpty())
                    <div class="related-articles">
                        <h3 class="related-title">Oxşar xəbərlər</h3>
                        <div class="related-grid">
                            @foreach($relatedPosts as $relatedPost)
                            <article class="related-card">
                                <a href="{{ $relatedPost->url }}" class="related-image">
                                    <img src="{{ $relatedPost->featured_image_thumb ?? asset('images/placeholder.jpg') }}" alt="{{ $relatedPost->title }}" loading="lazy">
                                </a>
                                <div class="related-content">
                                    @if($relatedPost->main_category)
                                    <span class="category-badge" data-category-id="{{ $relatedPost->main_category->id }}" style="background-color: {{ $relatedPost->main_category->color }};">
                                        {{ $relatedPost->main_category->name }}
                                    </span>
                                    @endif
                                    <h4 class="related-card-title">
                                        <a href="{{ $relatedPost->url }}">{{ $relatedPost->title }}</a>
                                    </h4>
                                    <span class="related-date">{{ $relatedPost->published_at->translatedFormat('d F Y') }}</span>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </article>

                @include('partials.sidebar')
            </div>
        </div>
    </section>
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
