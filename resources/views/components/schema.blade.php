{{-- Structured Data (Schema.org JSON-LD) --}}

@if($type === 'website')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebSite",
  "name": "OLAY.az",
  "alternateName": "Olay",
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
  "name": "OLAY.az",
  "alternateName": "Olay",
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
  "description": "{{ $article->meta_description }}",
  "image": {
    "@@type": "ImageObject",
    "url": "{{ $article->featured_image ?? asset('images/placeholder.jpg') }}",
    "width": 1200,
    "height": 630
  },
  "datePublished": "{{ $article->published_at->toIso8601String() }}",
  "dateModified": "{{ $article->updated_at->toIso8601String() }}",
  "author": {
    "@@type": "Person",
    "name": "{{ $article->author->name ?? 'OLAY.az' }}",
    @if($article->author)
    "url": "{{ route('search', ['q' => $article->author->name]) }}"
    @else
    "url": "{{ route('home') }}"
    @endif
  },
  "publisher": {
    "@@type": "Organization",
    "name": "OLAY.az",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/logo-cropped.png') }}",
      "width": 200,
      "height": 60
    }
  },
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ url()->current() }}"
  },
  "articleSection": "{{ $article->category->name ?? 'Xəbərlər' }}",
  "keywords": "{{ $article->meta_keywords }}",
  "wordCount": {{ count(array_filter(preg_split('/\s+/', strip_tags($article->content)))) }},
  "inLanguage": "az"
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
