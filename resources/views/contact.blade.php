@extends('layouts.app')

@php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'News24.az';
@endphp

@section('title', 'Əlaqə - ' . $siteName)

@section('seo')
    <x-seo
        :title="'Əlaqə - ' . $siteName"
        :description="$siteName . ' ilə əlaqə saxlayın. Ünvan, telefon, email və iş saatları. Biz sizin təkliflərinizə və suallarınıza həmişə açığıq.'"
        :keywords="'əlaqə, ' . strtolower($siteName) . ', bizimlə əlaqə, ünvan, telefon, email, iş saatları'"
        :ogType="'website'"
        :ogImage="asset('images/logo-cropped.png')"
        :canonical="route('contact')"
    />
@endsection

@section('schema')
    {{-- ContactPage Schema --}}
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "ContactPage",
      "name": "Əlaqə - News24.az",
      "description": "News24.az ilə əlaqə saxlayın",
      "url": "{{ route('contact') }}",
      "mainEntity": {
        "@@type": "NewsMediaOrganization",
        "name": "News24.az",
        "url": "{{ config('app.url') }}",
        "logo": {
          "@@type": "ImageObject",
          "url": "{{ asset('images/logo-cropped.png') }}"
        },
        "address": {
          "@@type": "PostalAddress",
          "streetAddress": "Bakı",
          "addressLocality": "Bakı",
          "addressCountry": "AZ"
        },
        "telephone": "{{ config_value('PHONE') }}",
        "email": "{{ config_value('EMAIL') }}",
        "contactPoint": {
          "@@type": "ContactPoint",
          "telephone": "{{ config_value('PHONE') }}",
          "email": "{{ config_value('EMAIL') }}",
          "contactType": "Customer Service",
          "areaServed": "AZ",
          "availableLanguage": ["Azerbaijani", "Russian"]
        }
      }
    }
    </script>

    {{-- BreadcrumbList Schema --}}
    <x-schema
        type="breadcrumb"
        :breadcrumbs="[
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            ['name' => 'Əlaqə', 'url' => route('contact')]
        ]"
    />
@endsection

@section('content')
    <!-- Contact Hero -->
    <section class="contact-hero">
        <div class="contact-hero-bg"></div>
        <div class="container">
            <div class="contact-hero-content">
                <h1 class="contact-hero-title">{{ $page->getContent('hero.title') }}</h1>
                <p class="contact-hero-subtitle">{{ $page->getContent('hero.subtitle') }}</p>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="contact-section">
        <div class="container">
            <!-- Contact Cards -->
            <div class="contact-cards">
                <div class="contact-card">
                    <div class="contact-card-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                    <div class="contact-card-content">
                        <h3>Ünvan</h3>
                        <p>Bakı, Azərbaycan</p>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-card-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="contact-card-content">
                        <h3>Əlaqə nömrələri</h3>
                        <p><a href="tel:{{ str_replace(' ', '', config_value('PHONE')) }}">{{ config_value('PHONE') }}</a></p>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-card-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 6l-10 7L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="contact-card-content">
                        <h3>E-mail ünvanımız</h3>
                        <p><a href="mailto:{{ config_value('EMAIL') }}">{{ config_value('EMAIL') }}</a></p>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="contact-social">
                <h3>Sosial Şəbəkələrdə Bizi İzləyin</h3>
                <p>Sosial şəbəkələrdə bizimlə əlaqə saxlayın və ən son xəbərlərdən xəbərdar olun</p>
                <div class="contact-social-links">
                    @if(config_value('INSTAGRAM'))
                    <a href="{{ config_value('INSTAGRAM') }}" target="_blank" class="contact-social-link instagram">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="2"/>
                            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/>
                            <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>
                        </svg>
                        <span>Instagram</span>
                    </a>
                    @endif

                    @if(config_value('FACEBOOK'))
                    <a href="{{ config_value('FACEBOOK') }}" target="_blank" class="contact-social-link facebook">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Facebook</span>
                    </a>
                    @endif

                    @if(config_value('YOUTUBE'))
                    <a href="{{ config_value('YOUTUBE') }}" target="_blank" class="contact-social-link youtube">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.46 5.33A2.78 2.78 0 003.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.46-5.25 29 29 0 00-.46-5.33z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.75 15.02l5.75-3.27-5.75-3.27v6.54z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>YouTube</span>
                    </a>
                    @endif

                    @if(config_value('TIKTOK'))
                    <a href="{{ config_value('TIKTOK') }}" target="_blank" class="contact-social-link tiktok">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>TikTok</span>
                    </a>
                    @endif

                    @if(config_value('TELEGRAM'))
                    <a href="{{ config_value('TELEGRAM') }}" target="_blank" class="contact-social-link telegram">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M21.5 5.5l-18 7 4.5 1.5 1.5 5 3-3.5 4.5 3.5 4.5-13.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Telegram</span>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Working Hours -->
            <div class="contact-hours">
                <h3>{{ $page->getContent('hours.title') }}</h3>
                <div class="hours-list">
                    @foreach($page->getContent('hours.schedule', []) as $item)
                        <div class="hours-item {{ !$item['active'] ? 'inactive' : '' }}">
                            <span class="day">{{ $item['day'] }}</span>
                            <span class="time">{{ $item['time'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Map Section -->
            <div class="contact-map-section">
                <h2 class="section-title">{{ $page->getContent('map.title') }}</h2>
                <div class="contact-map">
                    <iframe
                        src="{{ $page->getContent('map.embed_url') }}"
                        width="100%"
                        height="450"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>
@endsection
