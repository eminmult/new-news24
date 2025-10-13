{{-- SEO Meta Tags Component --}}
@php
    $mainInfo = \App\Models\MainInfo::getInstance();
    $siteName = $mainInfo?->site_name ?? 'OLAY.az';
    $siteDescription = $mainInfo?->meta_description ?? 'OLAY.az - Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar';
    $siteKeywords = $mainInfo?->meta_keywords ?? 'xəbərlər, azərbaycan xəbərləri, son xəbərlər, günün xəbərləri, olay.az';
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
