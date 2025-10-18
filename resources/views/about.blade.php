@extends('layouts.app')

@php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'News24.az';
@endphp

@section('title', 'Haqqımızda - ' . $siteName)

@section('seo')
    <x-seo
        :title="'Haqqımızda - ' . $siteName"
        :description="$siteName . ' - Azərbaycanın aparıcı xəbər portalı. Bizim missiyamız, tariximiz və komandamız haqqında ətraflı məlumat.'"
        :keywords="'haqqımızda, ' . strtolower($siteName) . ', azərbaycan media, xəbər portalı, komandamız, missiya'"
        :ogType="'website'"
        :ogImage="asset('images/logo-cropped.png')"
        :canonical="route('about')"
    />
@endsection

@section('schema')
    {{-- Organization Schema --}}
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "AboutPage",
      "name": "Haqqımızda - News24.az",
      "description": "News24.az - Azərbaycanın aparıcı xəbər portalı. Bizim missiyamız, tariximiz və komandamız haqqında ətraflı məlumat.",
      "url": "{{ route('about') }}",
      "mainEntity": {
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
        "foundingDate": "{{ $page->getContent('timeline.events.0.date', '2020') }}",
        "sameAs": [
          "{{ config_value('INSTAGRAM') }}",
          "{{ config_value('FACEBOOK') }}",
          "{{ config_value('YOUTUBE') }}",
          "{{ config_value('TELEGRAM') }}",
          "{{ config_value('TIKTOK') }}"
        ],
        "contactPoint": {
          "@@type": "ContactPoint",
          "telephone": "{{ config_value('PHONE') }}",
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
            ['name' => 'Haqqımızda', 'url' => route('about')]
        ]"
    />
@endsection

@section('content')
    <!-- About Hero -->
    <section class="about-hero">
        <div class="about-hero-bg"></div>
        <div class="container">
            <div class="about-hero-content">
                <h1 class="about-hero-title">{{ $page->getContent('hero.title') }}</h1>
                <p class="about-hero-subtitle">{{ $page->getContent('hero.subtitle') }}</p>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="about-content">
        <div class="container-about">
            <!-- Story Section -->
            <div class="about-section">
                <div class="about-row">
                    <div class="about-text">
                        <h2 class="about-section-title">{{ $page->getContent('story.title') }}</h2>
                        <p class="about-lead">{{ $page->getContent('story.lead') }}</p>
                        @foreach($page->getContent('story.paragraphs', []) as $paragraph)
                        <p>{{ $paragraph['text'] }}</p>
                        @endforeach
                    </div>
                    <div class="about-visual">
                        <div class="about-image-wrapper">
                            <img src="{{ asset('images/liatris-holding.jpg') }}" alt="Liatris Holding" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mission Section -->
            <div class="about-section about-mission">
                <div class="mission-content">
                    <h2 class="about-section-title centered">{{ $page->getContent('mission.title') }}</h2>
                    <p class="centered-text">{{ $page->getContent('mission.description') }}</p>

                    <div class="mission-grid">
                        @foreach($page->getContent('mission.cards', []) as $index => $card)
                        <div class="mission-card">
                            <div class="mission-icon">
                                <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                    <circle cx="24" cy="24" r="24" fill="url(#missionGradient{{ $index + 1 }})"/>
                                    @if($index == 0)
                                    <path d="M24 14v20m-7-7l7 7 7-7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    @elseif($index == 1)
                                    <path d="M16 24l6 6 12-12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    @else
                                    <path d="M24 16c-4.4 0-8 3.6-8 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8z" stroke="white" stroke-width="2" fill="none"/>
                                    @endif
                                    <defs>
                                        <linearGradient id="missionGradient{{ $index + 1 }}" x1="0" y1="0" x2="48" y2="48">
                                            @if($index == 0)
                                            <stop offset="0%" stop-color="#fc0067"/>
                                            <stop offset="100%" stop-color="#ab21f4"/>
                                            @elseif($index == 1)
                                            <stop offset="0%" stop-color="#35d388"/>
                                            <stop offset="100%" stop-color="#ffd525"/>
                                            @else
                                            <stop offset="0%" stop-color="#ff6b6b"/>
                                            <stop offset="100%" stop-color="#ffd525"/>
                                            @endif
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <h3>{{ $card['title'] }}</h3>
                            <p>{{ $card['text'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="about-stats">
                @foreach($page->getContent('stats', []) as $stat)
                <div class="stat-item">
                    <div class="stat-number" data-target="{{ $stat['value'] }}">0</div>
                    <div class="stat-label">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>

            <!-- Team Section -->
            <div class="about-section about-team">
                <h2 class="about-section-title centered">{{ $page->getContent('team.title', 'Redaksiya heyəti') }}</h2>
                <p class="centered-text">{{ $page->getContent('team.description', 'Peşəkar jurnalistlərdən ibarət komandamız') }}</p>

                <div class="team-grid">
                    @forelse($authors as $author)
                    <div class="team-card">
                        <div class="team-avatar">
                            <img src="{{ $author->avatar_thumb }}" alt="{{ $author->name }}" loading="lazy">
                            <div class="team-avatar-overlay"></div>
                        </div>
                        <div class="team-info">
                            <h3 class="team-name">{{ $author->name }}</h3>
                            <p class="team-position">{{ $author->bio ?: 'Müxbir' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="team-card">
                        <div class="team-avatar">
                            <img src="{{ asset('images/default-avatar.png') }}" alt="Redaksiya" loading="lazy">
                            <div class="team-avatar-overlay"></div>
                        </div>
                        <div class="team-info">
                            <h3 class="team-name">Redaksiya heyəti</h3>
                            <p class="team-position">Komandamız</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Timeline Section -->
            <div class="about-section about-timeline">
                <h2 class="about-section-title centered">{{ $page->getContent('timeline.title') }}</h2>
                <div class="timeline">
                    @foreach($page->getContent('timeline.events', []) as $event)
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <div class="timeline-date">{{ $event['date'] }}</div>
                            <h3>{{ $event['title'] }}</h3>
                            <p>{{ $event['text'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Animated Counter for Stats
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        }

        // Trigger animation when stats section is in view
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.stat-number');
                    counters.forEach(counter => animateCounter(counter));
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        const statsSection = document.querySelector('.about-stats');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }
    </script>
@endsection
