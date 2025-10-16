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
                <h2 class="about-section-title centered">{{ $page->getContent('team.title') }}</h2>
                <p class="centered-text">{{ $page->getContent('team.description') }}</p>

                <div class="team-grid">
                    @foreach($page->getContent('team.members', []) as $member)
                    <div class="team-card">
                        <div class="team-avatar">
                            @if(!empty($member['photo']))
                            <img src="{{ asset('storage/' . $member['photo']) }}" alt="{{ $member['name'] }}" loading="lazy">
                            @else
                            <img src="{{ asset('images/default-avatar.jpg') }}" alt="{{ $member['name'] }}" loading="lazy">
                            @endif
                            <div class="team-avatar-overlay"></div>
                        </div>
                        <div class="team-info">
                            <h3 class="team-name">{{ $member['name'] }}</h3>
                            <p class="team-position">{{ $member['position'] }}</p>
                            @if(!empty($member['social_instagram']))
                            <div class="team-social">
                                <a href="{{ $member['social_instagram'] }}" class="team-social-link" target="_blank" rel="noopener">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
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
