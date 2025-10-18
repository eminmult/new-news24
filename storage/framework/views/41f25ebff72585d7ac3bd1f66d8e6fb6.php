<?php
    $mainInfo = \App\Models\MainInfo::getInstance();
    $siteName = $mainInfo?->site_name ?? 'News24.az';
?>

<?php $__env->startSection('title', $siteName . ' - Əsas səhifə'); ?>

<?php $__env->startSection('seo'); ?>
    <?php if (isset($component)) { $__componentOriginal42da61123f891e63201d7be28f403427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42da61123f891e63201d7be28f403427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo','data' => ['title' => ($mainInfo?->meta_title ?? $siteName) . ' - Azərbaycanın aparıcı xəbər portalı','description' => $mainInfo?->meta_description ?? 'Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar. Siyasət, iqtisadiyyat, idman, mədəniyyət və daha çox.','keywords' => $mainInfo?->meta_keywords ?? 'xəbərlər, azərbaycan xəbərləri, son xəbərlər, günün xəbərləri, news24.az, siyasət, iqtisadiyyat, idman','ogType' => 'website','ogImage' => asset('images/newslogo3.svg'),'canonical' => route('home')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($mainInfo?->meta_title ?? $siteName) . ' - Azərbaycanın aparıcı xəbər portalı'),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mainInfo?->meta_description ?? 'Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar. Siyasət, iqtisadiyyat, idman, mədəniyyət və daha çox.'),'keywords' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mainInfo?->meta_keywords ?? 'xəbərlər, azərbaycan xəbərləri, son xəbərlər, günün xəbərləri, news24.az, siyasət, iqtisadiyyat, idman'),'ogType' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('website'),'ogImage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('images/newslogo3.svg')),'canonical' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('home'))]); ?>
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
    
    <?php if (isset($component)) { $__componentOriginal02fe06fff3546a293848e400b56fdd30 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal02fe06fff3546a293848e400b56fdd30 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema','data' => ['type' => 'website']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('schema'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'website']); ?>
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

    
    <?php if (isset($component)) { $__componentOriginal02fe06fff3546a293848e400b56fdd30 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal02fe06fff3546a293848e400b56fdd30 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema','data' => ['type' => 'organization','socialLinks' => [
            'instagram' => config_value('INSTAGRAM'),
            'facebook' => config_value('FACEBOOK'),
            'youtube' => config_value('YOUTUBE'),
            'telegram' => config_value('TELEGRAM'),
            'tiktok' => config_value('TIKTOK'),
            'phone' => config_value('PHONE'),
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('schema'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'organization','socialLinks' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            'instagram' => config_value('INSTAGRAM'),
            'facebook' => config_value('FACEBOOK'),
            'youtube' => config_value('YOUTUBE'),
            'telegram' => config_value('TELEGRAM'),
            'tiktok' => config_value('TIKTOK'),
            'phone' => config_value('PHONE'),
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
<main class="main">
    <!-- Trending Topics Carousel -->
    <?php if(request()->get('page', 1) == 1 && $importantPosts && $importantPosts->isNotEmpty()): ?>
    <section class="trending-section">
        <div class="container">
            <div class="trending-wrapper">
                <div class="trending-nav-buttons">
                    <button class="trending-nav-btn prev-trending" aria-label="Əvvəlki">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M12 15l-5-5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <button class="trending-nav-btn next-trending" aria-label="Növbəti">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M8 15l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
                <div class="trending-carousel">
                    <div class="trending-track">
                        <?php $__currentLoopData = $importantPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="trending-card">
                            <div class="trending-image <?php if(!$post->featured_image_thumb): ?> img-gradient-<?php echo e(($index % 8) + 1); ?> <?php endif; ?>">
                                <?php if($post->featured_image_thumb): ?>
                                <img src="<?php echo e($post->featured_image_thumb); ?>" alt="<?php echo e($post->title); ?>" loading="lazy">
                                <?php endif; ?>
                                <span class="trending-number"><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                            </div>
                            <div class="trending-content">
                                <?php if($post->main_category): ?>
                                <span class="category-tag category-<?php echo e($post->main_category->id); ?>">
                                    <?php echo e($post->main_category->name); ?>

                                </span>
                                <?php endif; ?>
                                <h3><a href="<?php echo e($post->url); ?>"><?php echo e($post->title); ?></a></h3>
                                <div class="card-meta">
                                    <span>📈 <?php echo e(number_format($post->views)); ?> baxış</span>
                                    <span><?php echo e($post->published_at->diffForHumans()); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Main Featured Section -->
    <?php if(request()->get('page', 1) == 1 && $mainFeaturedPosts && $mainFeaturedPosts->isNotEmpty()): ?>
    <section class="main-featured-section">
        <div class="container">
            <div class="main-featured-wrapper">
                <!-- Featured Slider (75%) -->
                <div class="main-featured-slider-wrapper">
                    <button class="main-featured-nav-btn prev-main-featured" aria-label="Əvvəlki">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>

                    <div class="main-featured-slider">
                        <div class="main-featured-track">
                            <?php $__currentLoopData = $mainFeaturedPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="main-featured-card">
                                <div class="card-image">
                                    <?php if($post->featured_image_large): ?>
                                        <img src="<?php echo e($post->featured_image_large); ?>" alt="<?php echo e($post->title); ?>" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                                    <?php else: ?>
                                        <div class="img-gradient-<?php echo e(($loop->index % 8) + 1); ?>" style="width: 100%; height: 100%;"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-content">
                                    <div class="card-header">
                                        <?php if($post->main_category): ?>
                                        <span class="category-badge category-<?php echo e($post->main_category->id); ?>">
                                            <?php echo e($post->main_category->name); ?>

                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="card-title"><a href="<?php echo e($post->url); ?>"><?php echo e($post->title); ?></a></h3>
                                    <span class="card-date"><?php echo e(format_date_az($post->published_at, 'd F Y, H:i')); ?></span>
                                </div>
                            </article>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <button class="main-featured-nav-btn next-main-featured" aria-label="Növbəti">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <!-- Side Image (25%) - Advertising Banner -->
                <?php
                    $adBanner = config_value('MAIN_FEATURED_AD_BANNER', '/images/ad-banner-264x528.png');
                ?>
                <?php if($adBanner): ?>
                <a href="<?php echo e(config_value('MAIN_FEATURED_AD_BANNER_LINK', '#')); ?>" target="_blank" rel="noopener" style="display: block; width: 100%; height: 100%;">
                    <img src="<?php echo e($adBanner); ?>" alt="Reklam" class="main-featured-side-image" style="width: 100%; height: 100%; object-fit: fill; border-radius: 20px;" loading="lazy">
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Breaking News Ticker -->
    <?php if($latestPosts && $latestPosts->count() > 0): ?>
    <section class="breaking-news" <?php if(request()->get('page', 1) != 1): ?> style="margin-top: 40px;" <?php endif; ?>>
        <div class="container">
            <div class="ticker-wrapper">
                <span class="ticker-label">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M9 0L3 9h4l-1 7 6-9H8l1-7z"/>
                    </svg>
                    Təcili xəbərlər
                </span>
                <div class="ticker-overflow">
                    <div class="ticker-content">
                        <?php $__currentLoopData = $latestPosts->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="ticker-item">
                                <a href="<?php echo e($post->url); ?>"><?php echo e($post->title); ?></a>
                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php $__currentLoopData = $latestPosts->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="ticker-item">
                                <a href="<?php echo e($post->url); ?>"><?php echo e($post->title); ?></a>
                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- YouTube Carousel Section -->
    <?php if(request()->get('page', 1) == 1 && $videoPosts && $videoPosts->isNotEmpty()): ?>
    <section class="youtube-carousel-section">
        <div class="container">
            <div class="youtube-carousel-header">
                <h2 class="section-title">
                    Video xəbərlər
                </h2>
            </div>

            <div class="youtube-carousel-wrapper">
                <button class="youtube-nav-btn prev-youtube" aria-label="Əvvəlki">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>

                <div class="youtube-carousel">
                    <div class="youtube-carousel-track">
                        <?php $__currentLoopData = $videoPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="yt-card">
                            <a href="<?php echo e($post->url); ?>" class="yt-thumbnail">
                                <?php if($post->featured_image_thumb): ?>
                                    <img src="<?php echo e($post->featured_image_thumb); ?>" alt="<?php echo e($post->title); ?>" class="yt-thumbnail-bg" loading="lazy">
                                <?php else: ?>
                                    <div class="img-gradient-<?php echo e(($loop->index % 8) + 1); ?> yt-thumbnail-bg"></div>
                                <?php endif; ?>
                                <div class="yt-play">
                                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                        <circle cx="24" cy="24" r="24" fill="rgba(255, 255, 255, 0.95)"/>
                                        <path d="M19 15l15 9-15 9V15z" fill="#ef4444"/>
                                    </svg>
                                </div>
                                <h3 class="yt-title"><?php echo e($post->title); ?></h3>
                            </a>
                        </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <button class="youtube-nav-btn next-youtube" aria-label="Növbəti">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- News Grid -->
    <section class="news-grid-section">
        <div class="container">
            <div class="grid-layout">
                <div class="main-column">
                    <div class="section-header">
                        <h2 class="section-title">Son xəbərlər</h2>
                    </div>

                    <div class="news-cards-grid">
                <?php $__currentLoopData = $latestPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="news-card">
                    <a href="<?php echo e($post->url); ?>">
                        <div class="card-image">
                            <?php if($post->featured_image_thumb): ?>
                                <img src="<?php echo e($post->featured_image_thumb); ?>" alt="<?php echo e($post->title); ?>" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                            <?php else: ?>
                                <div class="img-gradient-<?php echo e(($loop->index % 8) + 1); ?>" style="width: 100%; height: 100%;"></div>
                            <?php endif; ?>
                            <span class="news-card-date">
                                <?php if($post->published_at->isToday()): ?>
                                    <?php echo e($post->published_at->format('H:i')); ?>

                                <?php else: ?>
                                    <?php echo e(format_date_az($post->published_at, 'd M H:i')); ?>

                                <?php endif; ?>
                            </span>
                        </div>

                        <?php if($post->main_category): ?>
                        <span class="category-badge category-<?php echo e($post->main_category->id); ?>">
                            <?php echo e($post->main_category->name); ?>

                        </span>
                        <?php endif; ?>

                        <div class="card-content">
                            <h3 class="card-title"><?php echo e($post->title); ?></h3>
                        </div>
                    </a>
                </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <?php if($latestPosts->hasPages()): ?>
            <div class="pagination">
                <?php if($latestPosts->onFirstPage()): ?>
                    <button class="pagination-btn pagination-prev" disabled>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M10 12l-4-4 4-4"/>
                        </svg>
                    </button>
                <?php else: ?>
                    <a href="<?php echo e($latestPosts->previousPageUrl()); ?>" class="pagination-btn pagination-prev">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M10 12l-4-4 4-4"/>
                        </svg>
                    </a>
                <?php endif; ?>

                <?php
                    $currentPage = $latestPosts->currentPage();
                    $lastPage = $latestPosts->lastPage();

                    // Показываем 5 страниц: текущую, 2 до и 2 после
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);

                    // Корректируем если близко к началу или концу
                    if ($end - $start < 4) {
                        if ($start == 1) {
                            $end = min($lastPage, 5);
                        } else {
                            $start = max(1, $lastPage - 4);
                        }
                    }
                ?>

                <?php if($start > 1): ?>
                    <a href="<?php echo e($latestPosts->url(1)); ?>" class="pagination-btn pagination-page">1</a>
                    <?php if($start > 2): ?>
                        <span class="pagination-dots">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for($page = $start; $page <= $end; $page++): ?>
                    <?php if($page == $currentPage): ?>
                        <button class="pagination-btn pagination-page active"><?php echo e($page); ?></button>
                    <?php else: ?>
                        <a href="<?php echo e($latestPosts->url($page)); ?>" class="pagination-btn pagination-page"><?php echo e($page); ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($end < $lastPage): ?>
                    <?php if($end < $lastPage - 1): ?>
                        <span class="pagination-dots">...</span>
                    <?php endif; ?>
                    <a href="<?php echo e($latestPosts->url($lastPage)); ?>" class="pagination-btn pagination-page"><?php echo e($lastPage); ?></a>
                <?php endif; ?>

                <?php if($latestPosts->hasMorePages()): ?>
                    <a href="<?php echo e($latestPosts->nextPageUrl()); ?>" class="pagination-btn pagination-next">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M6 12l4-4-4-4"/>
                        </svg>
                    </a>
                <?php else: ?>
                    <button class="pagination-btn pagination-next" disabled>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M6 12l4-4-4-4"/>
                        </svg>
                    </button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
                </div>
                
            </div>
            
        </div>
    </section>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/home.blade.php ENDPATH**/ ?>