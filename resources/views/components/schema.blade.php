{{-- Structured Data (Schema.org JSON-LD) --}}

@if($type === 'website')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebSite",
  "name": "News24.az",
  "alternateName": "News24",
  "url": "{{ config('app.url') }}",
  "description": "Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar",
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
@endif

@if($type === 'organization')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsMediaOrganization",
  "name": "News24.az",
  "alternateName": "News24",
  "url": "{{ config('app.url') }}",
  "logo": {
    "@@type": "ImageObject",
    "url": "{{ asset('images/logo-cropped.png') }}",
    "width": 200,
    "height": 60
  },
  "description": "Azərbaycanın aparıcı xəbər portalı",
  "sameAs": [
    @php
      $socialLinksArray = array_filter([
        $socialLinks['instagram'] ?? null,
        $socialLinks['facebook'] ?? null,
        $socialLinks['youtube'] ?? null,
        $socialLinks['telegram'] ?? null,
        $socialLinks['tiktok'] ?? null,
      ]);
    @endphp
    {!! '"' . implode('","', $socialLinksArray) . '"' !!}
  ],
  "contactPoint": {
    "@@type": "ContactPoint",
    "telephone": "{{ $socialLinks['phone'] ?? '' }}",
    "contactType": "Customer Service",
    "areaServed": "AZ",
    "availableLanguage": ["Azerbaijani", "Russian"]
  }
}
</script>
@endif

@if($type === 'newsarticle' && isset($article))
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsArticle",
  "headline": "{{ $article->title }}",
  "alternativeHeadline": "{{ $article->meta_title }}",
  "description": "{{ $article->meta_description }}",
  "image": [
    @if($article->featured_image)
    {
      "@@type": "ImageObject",
      "url": "{{ $article->featured_image }}",
      "width": 1200,
      "height": 630,
      "caption": "{{ $article->title }}"
    }@if($article->hasMedia('post-gallery') && $article->getMedia('post-gallery')->count() > 0),@endif
    @endif
    @if($article->hasMedia('post-gallery'))
      @foreach($article->getMedia('post-gallery') as $media)
      {
        "@@type": "ImageObject",
        "url": "{{ $media->getFullUrl('webp') }}",
        "width": 1200,
        "height": 800,
        "caption": "{{ $article->title }}"
      }@if(!$loop->last),@endif
      @endforeach
    @endif
  ],
  "datePublished": "{{ $article->published_at->toIso8601String() }}",
  "dateModified": "{{ $article->updated_at->toIso8601String() }}",
  "author": {
    "@@type": "Person",
    "name": "{{ $article->author->name ?? 'News24.az' }}"@if($article->author && $article->author->avatar_thumb),
    "image": {
      "@@type": "ImageObject",
      "url": "{{ $article->author->avatar_thumb }}"
    }@endif
    @if($article->author)
    ,"url": "{{ route('search', ['q' => $article->author->name]) }}"
    @else
    ,"url": "{{ route('home') }}"
    @endif
  },
  "publisher": {
    "@@type": "NewsMediaOrganization",
    "name": "News24.az",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/logo-cropped.png') }}",
      "width": 200,
      "height": 60
    },
    "url": "{{ config('app.url') }}"
  },
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ $article->url }}"
  },
  "url": "{{ $article->url }}",
  "articleSection": "{{ $article->main_category->name ?? 'Xəbərlər' }}",
  @if($article->tags && $article->tags->count() > 0)
  "keywords": [
    @foreach($article->tags as $tag)
    "{{ $tag->name }}"@if(!$loop->last),@endif
    @endforeach
  ],
  @endif
  "wordCount": {{ count(array_filter(preg_split('/\s+/', strip_tags($article->content)))) }},
  "inLanguage": "az",
  "copyrightYear": {{ $article->published_at->year }},
  "copyrightHolder": {
    "@@type": "Organization",
    "name": "News24.az"
  },
  @if($article->views_count)
  "interactionStatistic": {
    "@@type": "InteractionCounter",
    "interactionType": "https://schema.org/ReadAction",
    "userInteractionCount": {{ $article->views_count }}
  },
  @endif
  "isAccessibleForFree": true,
  "isPartOf": {
    "@@type": "WebSite",
    "name": "News24.az",
    "url": "{{ config('app.url') }}"
  }
}
</script>
@endif

@if($type === 'breadcrumb' && isset($breadcrumbs))
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    @foreach($breadcrumbs as $index => $crumb)
    {
      "@@type": "ListItem",
      "position": {{ $index + 1 }},
      "name": "{{ $crumb['name'] }}",
      "item": "{{ $crumb['url'] }}"
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
@endif

@if($type === 'itemlist' && isset($items))
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "ItemList",
  "itemListElement": [
    @foreach($items as $index => $item)
    {
      "@@type": "ListItem",
      "position": {{ $index + 1 }},
      "url": "{{ $item->url }}",
      "name": "{{ $item->title }}"
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
@endif

@if($type === 'collectionpage' && isset($posts))
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "CollectionPage",
  "name": "{{ $pageTitle ?? 'Əsas səhifə - News24.az' }}",
  "description": "{{ $pageDescription ?? 'Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar' }}",
  "url": "{{ url()->current() }}",
  "inLanguage": "az",
  "isPartOf": {
    "@@type": "WebSite",
    "name": "News24.az",
    "url": "{{ config('app.url') }}"
  },
  "hasPart": [
    @foreach($posts as $index => $post)
    {
      "@@type": "NewsArticle",
      "headline": "{{ $post->title }}",
      "url": "{{ $post->url }}",
      "datePublished": "{{ $post->published_at->toIso8601String() }}",
      "dateModified": "{{ $post->updated_at->toIso8601String() }}",
      @if($post->featured_image)
      "image": {
        "@@type": "ImageObject",
        "url": "{{ $post->featured_image }}",
        "width": 1200,
        "height": 630
      },
      @endif
      "author": {
        "@@type": "Person",
        "name": "{{ $post->author->name ?? 'News24.az' }}"
      },
      "publisher": {
        "@@type": "Organization",
        "name": "News24.az",
        "logo": {
          "@@type": "ImageObject",
          "url": "{{ asset('images/logo-cropped.png') }}",
          "width": 200,
          "height": 60
        }
      },
      @if($post->main_category)
      "articleSection": "{{ $post->main_category->name }}",
      @endif
      "inLanguage": "az"
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
@endif

@if($type === 'webpage')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebPage",
  "name": "{{ $pageTitle ?? 'News24.az' }}",
  "description": "{{ $pageDescription ?? 'Azərbaycanın aparıcı xəbər portalı' }}",
  "url": "{{ url()->current() }}",
  "inLanguage": "az",
  "isPartOf": {
    "@@type": "WebSite",
    "name": "News24.az",
    "url": "{{ config('app.url') }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "News24.az",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/logo-cropped.png') }}",
      "width": 200,
      "height": 60
    }
  },
  "breadcrumb": {
    "@@type": "BreadcrumbList",
    "itemListElement": [
      {
        "@@type": "ListItem",
        "position": 1,
        "name": "Əsas səhifə",
        "item": "{{ route('home') }}"
      }
    ]
  }
}
</script>
@endif

@if($type === 'category' && isset($category))
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "CollectionPage",
  "name": "{{ $category->name }} xəbərləri",
  "description": "{{ $category->description ?: $category->name . ' kateqoriyasından ən son xəbərlər' }}",
  "url": "{{ route('category', $category->slug) }}",
  "inLanguage": "az",
  "about": {
    "@@type": "Thing",
    "name": "{{ $category->name }}",
    "description": "{{ $category->description ?: $category->name }}"
  },
  "isPartOf": {
    "@@type": "WebSite",
    "name": "News24.az",
    "url": "{{ config('app.url') }}"
  },
  "publisher": {
    "@@type": "NewsMediaOrganization",
    "name": "News24.az",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/logo-cropped.png') }}",
      "width": 200,
      "height": 60
    }
  },
  "breadcrumb": {
    "@@type": "BreadcrumbList",
    "itemListElement": [
      {
        "@@type": "ListItem",
        "position": 1,
        "name": "Əsas səhifə",
        "item": "{{ route('home') }}"
      },
      {
        "@@type": "ListItem",
        "position": 2,
        "name": "{{ $category->name }}",
        "item": "{{ route('category', $category->slug) }}"
      }
    ]
  }
}
</script>
@endif
