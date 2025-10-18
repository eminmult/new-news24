@extends('layouts.app')

@php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'News24.az';
    $mainInfo = \App\Models\MainInfo::getInstance();
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
        "foundingDate": "2018",
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
    <!-- Breadcrumbs -->
    <section class="breadcrumbs-section">
        <div class="container">
            <div class="breadcrumbs">
                <a href="{{ route('home') }}" class="breadcrumb-item">Ana səhifə</a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-item active">Haqqımızda</span>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-header">
                <h1 class="about-title">{{ $mainInfo->about_title ?? 'Haqqımızda' }}</h1>
                <p class="about-subtitle">{{ $mainInfo->about_subtitle ?? 'Azərbaycanın aparıcı xəbər portalı' }}</p>
            </div>

            <div class="about-content">
                <div class="about-intro">
                    {!! $mainInfo->about_intro ?? '<p><strong>News24.az</strong> onlayn informasiya portalıdır.</p>' !!}
                </div>

                <div class="about-values">
                    <h2 class="section-title">Bizim dəyərlərimiz</h2>
                    <div class="values-grid">
                        @foreach($page->getContent('mission.cards', []) as $index => $card)
                        <div class="value-card">
                            <div class="value-icon">
                                @if($index == 0)
                                📰
                                @elseif($index == 1)
                                ⚡
                                @elseif($index == 2)
                                ⚖️
                                @else
                                🌐
                                @endif
                            </div>
                            <h3>{{ $card['title'] }}</h3>
                            <p>{{ $card['text'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Founder Section -->
    <section class="team-section">
        <div class="container">
            <h2 class="section-title">Təsisçi</h2>
            <div class="founder-card">
                <div class="founder-photo">
                    <div class="photo-placeholder">
                        <img src="{{ $mainInfo->founder_image ?? 'https://olay.az/images/liatris-holding.jpg' }}"
                             alt="{{ $mainInfo->founder_name ?? 'Liatris Holding MMC' }}"
                             loading="lazy">
                    </div>
                </div>
                <div class="founder-info">
                    <h3 class="founder-name">{{ $mainInfo->founder_name ?? 'Liatris Holding MMC' }}</h3>
                    <p class="founder-title">{{ $mainInfo->founder_title ?? 'Təsisçi şirkət' }}</p>
                    <p class="founder-description">{{ $mainInfo->founder_description ?? 'News24.az "Liatris Holding" MMC-nin tərkibində fəaliyyət göstərir.' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Editorial Team Section -->
    <section class="team-section">
        <div class="container">
            <h2 class="section-title">Redaksiya heyəti</h2>
            <p class="team-subtitle">Peşəkar jurnalistlərdən ibarət komandamız</p>

            <div class="team-grid">
                @forelse($authors as $author)
                <div class="team-member">
                    <div class="member-photo">
                        <div class="photo-placeholder">
                            <img src="{{ $author->avatar_thumb }}" alt="{{ $author->name }}" loading="lazy">
                        </div>
                    </div>
                    <h3 class="member-name">{{ $author->name }}</h3>
                    <p class="member-role">{{ $author->bio ?: 'Müxbir' }}</p>
                </div>
                @empty
                <div class="team-member">
                    <div class="member-photo">
                        <div class="photo-placeholder">
                            <img src="{{ asset('images/default-avatar.png') }}" alt="Redaksiya" loading="lazy">
                        </div>
                    </div>
                    <h3 class="member-name">Redaksiya heyəti</h3>
                    <p class="member-role">Komandamız</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <h2 class="section-title">Bizimlə əlaqə</h2>
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <h3>Ünvan</h3>
                    <p>{!! $mainInfo->address ?? 'Nəriman rayonu, Mixail Koveroçkin küçəsi 38<br>(Hərbi Prokurorluğun yanı)' !!}</p>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                    </div>
                    <h3>Telefon</h3>
                    <p>
                        @if($mainInfo && $mainInfo->phones)
                            @foreach($mainInfo->phones as $phone)
                                {{ $phone }}@if(!$loop->last)<br>@endif
                            @endforeach
                        @else
                            050 970 77 66<br>012 498 17 77
                        @endif
                    </p>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <h3>E-mail</h3>
                    <p>
                        @if($mainInfo && $mainInfo->emails)
                            @foreach($mainInfo->emails as $email)
                                {{ $email }}@if(!$loop->last)<br>@endif
                            @endforeach
                        @else
                            info@news24.az
                        @endif
                    </p>
                </div>
            </div>

            <!-- Map Section -->
            <div class="map-section">
                <h3 class="section-title">Ofisimizin yeri</h3>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3039.0876582088947!2d49.83873731562238!3d40.38294207936527!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d9c8c6c5555%3A0x1234567890abcdef!2sMikhail%20Koverochkin%20Street%2038%2C%20Baku%2C%20Azerbaijan!5e0!3m2!1sen!2saz!4v1234567890123!5m2!1sen!2saz"
                        width="100%"
                        height="450"
                        style="border:0; border-radius: 20px;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <div class="cooperation-info">
                <h3>Əməkdaşlıq</h3>
                @if($mainInfo && $mainInfo->cooperation_text)
                    {!! $mainInfo->cooperation_text !!}
                @else
                    <p>News24.az aşağıdakı sahələrdə əməkdaşlığa açıqdır:</p>
                    <ul>
                        <li>Saytda reklam yerləşdirilməsi</li>
                        <li>Link mübadiləsi</li>
                        <li>Promo materialların paylaşılması</li>
                        <li>Məhsul və kampaniya xəbərlərinin dərc edilməsi</li>
                        <li>Uzunmüddətli tərəfdaşlıq üçün xüsusi endirimlər</li>
                        <li>Hazır materialı olmayan şirkətlər üçün reklam xidmətləri</li>
                    </ul>
                @endif
                <p><strong>Qeyd:</strong> Saytdakı bütün materialların müəllif hüquqları qorunur. Materiallardan istifadə edərkən mənbəyə istinad mütləqdir.</p>
            </div>
        </div>
    </section>
@endsection
