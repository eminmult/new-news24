{{-- Structured Data (Schema.org JSON-LD) - Google 2024/2025 Best Practices --}}

@if($type === 'website')
@php
$websiteSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'News24.az',
    'alternateName' => 'News24',
    'url' => config('app.url'),
    'description' => 'Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar',
    'inLanguage' => 'az',
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => [
            '@type' => 'EntryPoint',
            'urlTemplate' => config('app.url') . '/search?q={search_term_string}'
        ],
        'query-input' => 'required name=search_term_string'
    ]
];
@endphp
<script type="application/ld+json">
{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'organization')
@php
$socialLinksArray = array_values(array_filter([
    $socialLinks['instagram'] ?? null,
    $socialLinks['facebook'] ?? null,
    $socialLinks['youtube'] ?? null,
    $socialLinks['telegram'] ?? null,
    $socialLinks['tiktok'] ?? null,
]));

$organizationSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'NewsMediaOrganization',
    'name' => 'News24.az',
    'alternateName' => 'News24',
    'url' => config('app.url'),
    'logo' => [
        '@type' => 'ImageObject',
        'url' => asset('images/logo-cropped.png'),
        'width' => 200,
        'height' => 60
    ],
    'description' => 'Azərbaycanın aparıcı xəbər portalı',
    'sameAs' => $socialLinksArray
];

if (!empty($socialLinks['phone'])) {
    $organizationSchema['contactPoint'] = [
        '@type' => 'ContactPoint',
        'telephone' => $socialLinks['phone'],
        'contactType' => 'Customer Service',
        'areaServed' => 'AZ',
        'availableLanguage' => ['Azerbaijani', 'Russian']
    ];
}
@endphp
<script type="application/ld+json">
{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'newsarticle' && isset($article))
@php
// Build image array
$images = [];
if ($article->featured_image) {
    $images[] = [
        '@type' => 'ImageObject',
        'url' => $article->featured_image,
        'width' => 1200,
        'height' => 630,
        'caption' => $article->title
    ];
}
if ($article->hasMedia('post-gallery')) {
    foreach ($article->getMedia('post-gallery') as $media) {
        $images[] = [
            '@type' => 'ImageObject',
            'url' => $media->getFullUrl('webp'),
            'width' => 1200,
            'height' => 800,
            'caption' => $article->title
        ];
    }
}

// Build author object (Enhanced for E-A-T)
$author = [
    '@type' => 'Person',
    'name' => $article->author->name ?? 'News24.az',
    'jobTitle' => 'Jurnalist',
    'worksFor' => [
        '@type' => 'NewsMediaOrganization',
        'name' => 'News24.az',
        'url' => config('app.url')
    ]
];
if ($article->author && $article->author->avatar_thumb) {
    $author['image'] = [
        '@type' => 'ImageObject',
        'url' => $article->author->avatar_thumb
    ];
}
if ($article->author) {
    $author['url'] = route('author.show', $article->author->slug);
    
    // Add bio if available
    if ($article->author->bio) {
        $author['description'] = $article->author->bio;
    }
    
    // Add expertise areas (categories this author writes about)
    $authorCategories = \App\Models\Post::published()
        ->where('author_id', $article->author->id)
        ->with('categories')
        ->get()
        ->map(fn($p) => $p->main_category?->name)->filter()
        ->filter()
        ->unique()
        ->take(3)
        ->toArray();
    
    if (!empty($authorCategories)) {
        $author['knowsAbout'] = $authorCategories;
    }
} else {
    $author['url'] = route('home');
}

// Build keywords array
$keywords = [];
if ($article->tags && $article->tags->count() > 0) {
    $keywords = $article->tags->pluck('name')->toArray();
}

// Extract article body (plain text, max 5000 chars for performance)
$articleBody = strip_tags($article->content);
$articleBody = preg_replace('/\s+/', ' ', $articleBody);
$articleBody = trim($articleBody);
if (mb_strlen($articleBody) > 5000) {
    $articleBody = mb_substr($articleBody, 0, 5000) . '...';
}

// Build speakable property for voice search (first 2 paragraphs or first 200 chars)
$speakableText = strip_tags($article->content);
$speakableText = preg_replace('/\s+/', ' ', $speakableText);
$speakableText = trim($speakableText);
$speakableText = mb_substr($speakableText, 0, 200);
if (mb_strlen($speakableText) < mb_strlen(strip_tags($article->content))) {
    $speakableText .= '...';
}

// Calculate freshness (2025+ feature)
$hoursSincePublished = $article->published_at->diffInHours(now());
$isFresh = $hoursSincePublished < 24; // 24 saat ucun yayınlanan xeberler "fresh"
$isBreaking = $hoursSincePublished < 2; // 2 saat ucun yayınlanan xeberler "breaking"

// Build main schema
$newsArticleSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $article->title,
    'description' => $article->meta_description,
    'image' => $images,
    'datePublished' => $article->published_at->toIso8601String(),
    'dateModified' => $article->updated_at->toIso8601String(),
    'author' => $author,
    'publisher' => [
        '@type' => 'NewsMediaOrganization',
        'name' => 'News24.az',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo-cropped.png'),
            'width' => 200,
            'height' => 60
        ],
        'url' => config('app.url')
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => $article->url
    ],
    'url' => $article->url,
    'articleSection' => $article->main_category->name ?? 'Xəbərlər',
    'articleBody' => $articleBody,
    'wordCount' => count(array_filter(preg_split('/\s+/', strip_tags($article->content)))),
    'inLanguage' => 'az',
    'copyrightYear' => $article->published_at->year,
    'copyrightHolder' => [
        '@type' => 'Organization',
        'name' => 'News24.az'
    ],
    'isAccessibleForFree' => true,
    'isPartOf' => [
        '@type' => 'WebSite',
        'name' => 'News24.az',
        'url' => config('app.url')
    ],
    'speakable' => [
        '@type' => 'SpeakableSpecification',
        'cssSelector' => ['h1', '.article-content p:first-of-type'],
        'xpath' => ['/html/head/title', '/html/body/article/div[1]/p[1]']
    ]
];

// Add freshness signals (2025+)
if ($isFresh) {
    $newsArticleSchema['freshnessScore'] = max(0, 100 - ($hoursSincePublished * 2)); // 0-100 score
}

if ($isBreaking) {
    $newsArticleSchema['breakingNews'] = true;
}

// Add optional fields
if (!empty($article->meta_title) && $article->meta_title !== $article->title) {
    $newsArticleSchema['alternativeHeadline'] = $article->meta_title;
}

if (!empty($keywords)) {
    $newsArticleSchema['keywords'] = $keywords;
}

if ($article->views_count) {
    $newsArticleSchema['interactionStatistic'] = [
        '@type' => 'InteractionCounter',
        'interactionType' => 'https://schema.org/ReadAction',
        'userInteractionCount' => $article->views_count
    ];
}
@endphp
<script type="application/ld+json">
{!! json_encode($newsArticleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'breadcrumb' && isset($breadcrumbs))
@php
$breadcrumbItems = [];
foreach ($breadcrumbs as $index => $crumb) {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'name' => $crumb['name'],
        'item' => $crumb['url']
    ];
}

$breadcrumbSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => $breadcrumbItems
];
@endphp
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'itemlist' && isset($items))
@php
$itemListElements = [];
foreach ($items as $index => $item) {
    $itemListElements[] = [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'url' => $item->url,
        'name' => $item->title
    ];
}

$itemListSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'itemListElement' => $itemListElements
];
@endphp
<script type="application/ld+json">
{!! json_encode($itemListSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'collectionpage' && isset($posts))
@php
$collectionItems = [];
foreach ($posts as $post) {
    $item = [
        '@type' => 'NewsArticle',
        'headline' => $post->title,
        'url' => $post->url,
        'datePublished' => $post->published_at->toIso8601String(),
        'dateModified' => $post->updated_at->toIso8601String(),
        'author' => [
            '@type' => 'Person',
            'name' => $post->author->name ?? 'News24.az'
        ],
        'publisher' => [
            '@type' => 'NewsMediaOrganization',
            'name' => 'News24.az',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('images/logo-cropped.png'),
                'width' => 200,
                'height' => 60
            ]
        ],
        'inLanguage' => 'az'
    ];
    
    if ($post->featured_image) {
        $item['image'] = [
            '@type' => 'ImageObject',
            'url' => $post->featured_image,
            'width' => 1200,
            'height' => 630
        ];
    }
    
    if ($post->main_category) {
        $item['articleSection'] = $post->main_category->name;
    }
    
    $collectionItems[] = $item;
}

$collectionPageSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $pageTitle ?? 'Əsas səhifə - News24.az',
    'description' => $pageDescription ?? 'Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar',
    'url' => url()->current(),
    'inLanguage' => 'az',
    'isPartOf' => [
        '@type' => 'WebSite',
        'name' => 'News24.az',
        'url' => config('app.url')
    ],
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => $collectionItems
    ]
];
@endphp
<script type="application/ld+json">
{!! json_encode($collectionPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'webpage')
@php
$webPageSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => $pageTitle ?? 'News24.az',
    'description' => $pageDescription ?? 'Azərbaycanın aparıcı xəbər portalı',
    'url' => url()->current(),
    'inLanguage' => 'az',
    'isPartOf' => [
        '@type' => 'WebSite',
        'name' => 'News24.az',
        'url' => config('app.url')
    ],
    'publisher' => [
        '@type' => 'NewsMediaOrganization',
        'name' => 'News24.az',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo-cropped.png'),
            'width' => 200,
            'height' => 60
        ]
    ],
    'breadcrumb' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Əsas səhifə',
                'item' => route('home')
            ]
        ]
    ]
];
@endphp
<script type="application/ld+json">
{!! json_encode($webPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'category' && isset($category))
@php
$categorySchema = [
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $category->name . ' xəbərləri',
    'description' => $category->description ?: $category->name . ' kateqoriyasından ən son xəbərlər',
    'url' => route('category', $category->slug),
    'inLanguage' => 'az',
    'about' => [
        '@type' => 'Thing',
        'name' => $category->name,
        'description' => $category->description ?: $category->name
    ],
    'isPartOf' => [
        '@type' => 'WebSite',
        'name' => 'News24.az',
        'url' => config('app.url')
    ],
    'publisher' => [
        '@type' => 'NewsMediaOrganization',
        'name' => 'News24.az',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo-cropped.png'),
            'width' => 200,
            'height' => 60
        ]
    ],
    'breadcrumb' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Əsas səhifə',
                'item' => route('home')
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $category->name,
                'item' => route('category', $category->slug)
            ]
        ]
    ]
];
@endphp
<script type="application/ld+json">
{!! json_encode($categorySchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif
