{{-- SEO Meta Tags Component --}}
@php
    $mainInfo = \App\Models\MainInfo::getInstance();
    $siteName = $mainInfo?->site_name ?? 'News24.az';
    $siteDescription = $mainInfo?->meta_description ?? 'News24.az - Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar';
    $siteKeywords = $mainInfo?->meta_keywords ?? 'xəbərlər, azərbaycan xəbərləri, son xəbərlər, günün xəbərləri, news24.az';
@endphp

{{-- Basic Meta Tags --}}
<meta name="description" content="{{ $description ?? $siteDescription }}">
<meta name="keywords" content="{{ $keywords ?? $siteKeywords }}">
<meta name="author" content="{{ $siteName }}">
<meta name="robots" content="{{ $robots ?? 'index, follow' }}">
<meta name="googlebot" content="{{ $robots ?? 'index, follow' }}">
<link rel="canonical" href="{{ $canonical ?? url()->current() }}">

{{-- Open Graph Meta Tags --}}
<meta property="og:locale" content="az_AZ">
<meta property="og:type" content="{{ $ogType ?? 'website' }}">
<meta property="og:title" content="{{ $ogTitle ?? $title ?? $siteName }}">
<meta property="og:description" content="{{ $ogDescription ?? $description ?? $siteDescription }}">
<meta property="og:url" content="{{ $ogUrl ?? url()->current() }}">
<meta property="og:site_name" content="{{ $siteName }}">
@if(isset($ogImage))
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:type" content="image/jpeg">
@endif
@if(isset($publishedTime))
<meta property="article:published_time" content="{{ $publishedTime }}">
@endif
@if(isset($modifiedTime))
<meta property="article:modified_time" content="{{ $modifiedTime }}">
@endif
@if(isset($section))
<meta property="article:section" content="{{ $section }}">
@endif
@if(isset($tags))
@foreach($tags as $tag)
<meta property="article:tag" content="{{ $tag }}">
@endforeach
@endif

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="{{ $twitterCard ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $twitterTitle ?? $ogTitle ?? $title ?? $siteName }}">
<meta name="twitter:description" content="{{ $twitterDescription ?? $ogDescription ?? $description ?? $siteDescription }}">
@if(isset($ogImage))
<meta name="twitter:image" content="{{ $ogImage }}">
@endif
@if(isset($twitterSite))
<meta name="twitter:site" content="{{ $twitterSite }}">
@endif
@if(isset($twitterCreator))
<meta name="twitter:creator" content="{{ $twitterCreator }}">
@endif

{{-- Additional Meta Tags --}}
<meta name="language" content="Azerbaijani">
<meta http-equiv="content-language" content="az">
<meta name="geo.region" content="AZ">
<meta name="geo.placename" content="Azerbaijan">

{{-- Facebook Meta Tags --}}
<meta property="fb:app_id" content="{{ config_value('FACEBOOK_APP_ID') }}">

{{-- Additional Open Graph Tags --}}
@if(isset($ogPublishedTime))
<meta property="article:published_time" content="{{ $ogPublishedTime }}">
@endif
@if(isset($ogModifiedTime))
<meta property="article:modified_time" content="{{ $ogModifiedTime }}">
@endif
@if(isset($ogAuthor))
<meta property="article:author" content="{{ $ogAuthor }}">
@endif

{{-- Mobile App Meta Tags --}}
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ $siteName }}">

{{-- News Specific Meta Tags --}}
<meta name="news_keywords" content="{{ $keywords ?? $siteKeywords }}">
@if(isset($publishedTime))
<meta name="article:published_time" content="{{ $publishedTime }}">
<meta name="publication_date" content="{{ $publishedTime }}">
@endif

{{-- Article Freshness Signals (2025+) --}}
@if(isset($publishedTime))
@php
    $publishedDate = \Carbon\Carbon::parse($publishedTime);
    $hoursSincePublished = $publishedDate->diffInHours(now());
    $isFresh = $hoursSincePublished < 24; // 24 saat içinde yayınlanan haberler "fresh"
@endphp
@if($isFresh)
<meta name="article:freshness" content="fresh">
<meta name="news:breaking" content="true">
@endif
<meta name="article:age_hours" content="{{ $hoursSincePublished }}">
@endif

{{-- Last Modified Date (Article Freshness) --}}
@if(isset($modifiedTime) && isset($publishedTime) && $modifiedTime !== $publishedTime)
<meta name="last-modified" content="{{ $modifiedTime }}">
<meta name="article:updated_time" content="{{ $modifiedTime }}">
@endif

{{-- RSS Feed Link --}}
<link rel="alternate" type="application/rss+xml" title="{{ $siteName }} RSS Feed" href="{{ route('feed.rss') }}">
<link rel="alternate" type="application/atom+xml" title="{{ $siteName }} Atom Feed" href="{{ route('feed.atom') }}">

{{-- Preconnect to External Domains (Performance) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="dns-prefetch" href="https://www.google-analytics.com">
<link rel="dns-prefetch" href="https://www.googletagmanager.com">

{{-- Core Web Vitals Optimizations --}}
@if(isset($ogImage))
<link rel="preload" as="image" href="{{ $ogImage }}" fetchpriority="high">
@endif
