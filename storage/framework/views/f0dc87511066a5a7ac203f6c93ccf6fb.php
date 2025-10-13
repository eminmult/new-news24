<?php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'OLAY.az';
?>

<?php $__env->startSection('title', 'Haqqımızda - ' . $siteName); ?>

<?php $__env->startSection('seo'); ?>
    <?php if (isset($component)) { $__componentOriginal42da61123f891e63201d7be28f403427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42da61123f891e63201d7be28f403427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo','data' => ['title' => 'Haqqımızda - ' . $siteName,'description' => $siteName . ' - Azərbaycanın aparıcı xəbər portalı. Bizim missiyamız, tariximiz və komandamız haqqında ətraflı məlumat.','keywords' => 'haqqımızda, ' . strtolower($siteName) . ', azərbaycan media, xəbər portalı, komandamız, missiya','ogType' => 'website','ogImage' => asset('images/logo-cropped.png'),'canonical' => route('about')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Haqqımızda - ' . $siteName),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($siteName . ' - Azərbaycanın aparıcı xəbər portalı. Bizim missiyamız, tariximiz və komandamız haqqında ətraflı məlumat.'),'keywords' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('haqqımızda, ' . strtolower($siteName) . ', azərbaycan media, xəbər portalı, komandamız, missiya'),'ogType' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('website'),'ogImage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('images/logo-cropped.png')),'canonical' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('about'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal42da61123f891e63201d7be28f403427)): ?>
<?php $attributes = $__attributesOriginal42da61123f891e63201d7be28f403427; ?>
<?php unset($__attributesOriginal42da61123f891e63201d7be28f403427); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal42da61123f891e63201d7be28f403427)): ?>
<?php $component = $__componentOriginal42da61123f891e63201d7be28f403427; ?>
<?php unset($__componentOriginal42da61123f891e63201d7be28f403427); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('schema'); ?>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "AboutPage",
      "name": "Haqqımızda - OLAY.az",
      "description": "OLAY.az - Azərbaycanın aparıcı xəbər portalı. Bizim missiyamız, tariximiz və komandamız haqqında ətraflı məlumat.",
      "url": "<?php echo e(route('about')); ?>",
      "mainEntity": {
        "@type": "NewsMediaOrganization",
        "name": "OLAY.az",
        "alternateName": "Olay",
        "url": "<?php echo e(config('app.url')); ?>",
        "logo": {
          "@type": "ImageObject",
          "url": "<?php echo e(asset('images/logo-cropped.png')); ?>",
          "width": 200,
          "height": 60
        },
        "description": "Azərbaycanın aparıcı xəbər portalı",
        "foundingDate": "<?php echo e($page->getContent('timeline.events.0.date', '2020')); ?>",
        "sameAs": [
          "<?php echo e(config_value('INSTAGRAM')); ?>",
          "<?php echo e(config_value('FACEBOOK')); ?>",
          "<?php echo e(config_value('YOUTUBE')); ?>",
          "<?php echo e(config_value('TELEGRAM')); ?>",
          "<?php echo e(config_value('TIKTOK')); ?>"
        ],
        "contactPoint": {
          "@type": "ContactPoint",
          "telephone": "<?php echo e(config_value('PHONE')); ?>",
          "contactType": "Customer Service",
          "areaServed": "AZ",
          "availableLanguage": ["Azerbaijani", "Russian"]
        }
      }
    }
    </script>

    
    <?php if (isset($component)) { $__componentOriginal02fe06fff3546a293848e400b56fdd30 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal02fe06fff3546a293848e400b56fdd30 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema','data' => ['type' => 'breadcrumb','breadcrumbs' => [
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            ['name' => 'Haqqımızda', 'url' => route('about')]
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('schema'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'breadcrumb','breadcrumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            ['name' => 'Haqqımızda', 'url' => route('about')]
        ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal02fe06fff3546a293848e400b56fdd30)): ?>
<?php $attributes = $__attributesOriginal02fe06fff3546a293848e400b56fdd30; ?>
<?php unset($__attributesOriginal02fe06fff3546a293848e400b56fdd30); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal02fe06fff3546a293848e400b56fdd30)): ?>
<?php $component = $__componentOriginal02fe06fff3546a293848e400b56fdd30; ?>
<?php unset($__componentOriginal02fe06fff3546a293848e400b56fdd30); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- About Hero -->
    <section class="about-hero">
        <div class="about-hero-bg"></div>
        <div class="container">
            <div class="about-hero-content">
                <h1 class="about-hero-title"><?php echo e($page->getContent('hero.title')); ?></h1>
                <p class="about-hero-subtitle"><?php echo e($page->getContent('hero.subtitle')); ?></p>
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
                        <h2 class="about-section-title"><?php echo e($page->getContent('story.title')); ?></h2>
                        <p class="about-lead"><?php echo e($page->getContent('story.lead')); ?></p>
                        <?php $__currentLoopData = $page->getContent('story.paragraphs', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($paragraph['text']); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="about-visual">
                        <div class="about-image-wrapper">
                            <img src="<?php echo e(asset('images/liatris-holding.jpg')); ?>" alt="Liatris Holding" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mission Section -->
            <div class="about-section about-mission">
                <div class="mission-content">
                    <h2 class="about-section-title centered"><?php echo e($page->getContent('mission.title')); ?></h2>
                    <p class="centered-text"><?php echo e($page->getContent('mission.description')); ?></p>

                    <div class="mission-grid">
                        <?php $__currentLoopData = $page->getContent('mission.cards', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mission-card">
                            <div class="mission-icon">
                                <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                    <circle cx="24" cy="24" r="24" fill="url(#missionGradient<?php echo e($index + 1); ?>)"/>
                                    <?php if($index == 0): ?>
                                    <path d="M24 14v20m-7-7l7 7 7-7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <?php elseif($index == 1): ?>
                                    <path d="M16 24l6 6 12-12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <?php else: ?>
                                    <path d="M24 16c-4.4 0-8 3.6-8 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8z" stroke="white" stroke-width="2" fill="none"/>
                                    <?php endif; ?>
                                    <defs>
                                        <linearGradient id="missionGradient<?php echo e($index + 1); ?>" x1="0" y1="0" x2="48" y2="48">
                                            <?php if($index == 0): ?>
                                            <stop offset="0%" stop-color="#fc0067"/>
                                            <stop offset="100%" stop-color="#ab21f4"/>
                                            <?php elseif($index == 1): ?>
                                            <stop offset="0%" stop-color="#35d388"/>
                                            <stop offset="100%" stop-color="#ffd525"/>
                                            <?php else: ?>
                                            <stop offset="0%" stop-color="#ff6b6b"/>
                                            <stop offset="100%" stop-color="#ffd525"/>
                                            <?php endif; ?>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <h3><?php echo e($card['title']); ?></h3>
                            <p><?php echo e($card['text']); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="about-stats">
                <?php $__currentLoopData = $page->getContent('stats', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="stat-item">
                    <div class="stat-number" data-target="<?php echo e($stat['value']); ?>">0</div>
                    <div class="stat-label"><?php echo e($stat['label']); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Team Section -->
            <div class="about-section about-team">
                <h2 class="about-section-title centered"><?php echo e($page->getContent('team.title')); ?></h2>
                <p class="centered-text"><?php echo e($page->getContent('team.description')); ?></p>

                <div class="team-grid">
                    <?php $__currentLoopData = $page->getContent('team.members', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="team-card">
                        <div class="team-avatar">
                            <?php if(!empty($member['photo'])): ?>
                            <img src="<?php echo e(asset('storage/' . $member['photo'])); ?>" alt="<?php echo e($member['name']); ?>" loading="lazy">
                            <?php else: ?>
                            <img src="<?php echo e(asset('images/default-avatar.jpg')); ?>" alt="<?php echo e($member['name']); ?>" loading="lazy">
                            <?php endif; ?>
                            <div class="team-avatar-overlay"></div>
                        </div>
                        <div class="team-info">
                            <h3 class="team-name"><?php echo e($member['name']); ?></h3>
                            <p class="team-position"><?php echo e($member['position']); ?></p>
                            <?php if(!empty($member['social_instagram'])): ?>
                            <div class="team-social">
                                <a href="<?php echo e($member['social_instagram']); ?>" class="team-social-link" target="_blank" rel="noopener">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Timeline Section -->
            <div class="about-section about-timeline">
                <h2 class="about-section-title centered"><?php echo e($page->getContent('timeline.title')); ?></h2>
                <div class="timeline">
                    <?php $__currentLoopData = $page->getContent('timeline.events', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <div class="timeline-date"><?php echo e($event['date']); ?></div>
                            <h3><?php echo e($event['title']); ?></h3>
                            <p><?php echo e($event['text']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/about.blade.php ENDPATH**/ ?>