<?php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'OLAY.az';
?>

<?php $__env->startSection('title', $category->name . ' - ' . $siteName); ?>

<?php $__env->startSection('seo'); ?>
    <?php if (isset($component)) { $__componentOriginal42da61123f891e63201d7be28f403427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42da61123f891e63201d7be28f403427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo','data' => ['title' => $category->name . ' - ' . $siteName,'description' => $category->description ?: $category->name . ' kateqoriyasından ən son xəbərlər. ' . $siteName . ' - Azərbaycanın aparıcı xəbər portalı.','keywords' => $category->name . ', ' . $category->name . ' xəbərləri, azərbaycan xəbərləri, son xəbərlər, ' . strtolower($siteName),'ogType' => 'website','ogImage' => asset('images/logo-cropped.png'),'canonical' => route('category', $category->slug)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category->name . ' - ' . $siteName),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category->description ?: $category->name . ' kateqoriyasından ən son xəbərlər. ' . $siteName . ' - Azərbaycanın aparıcı xəbər portalı.'),'keywords' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category->name . ', ' . $category->name . ' xəbərləri, azərbaycan xəbərləri, son xəbərlər, ' . strtolower($siteName)),'ogType' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('website'),'ogImage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('images/logo-cropped.png')),'canonical' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('category', $category->slug))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema','data' => ['type' => 'breadcrumb','breadcrumbs' => [
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            ['name' => $category->name, 'url' => route('category', $category->slug)]
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('schema'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'breadcrumb','breadcrumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            ['name' => $category->name, 'url' => route('category', $category->slug)]
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

    
    <?php if($posts->isNotEmpty()): ?>
    <?php if (isset($component)) { $__componentOriginal02fe06fff3546a293848e400b56fdd30 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal02fe06fff3546a293848e400b56fdd30 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema','data' => ['type' => 'itemlist','items' => $posts]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('schema'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'itemlist','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($posts)]); ?>
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
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <a href="<?php echo e(route('home')); ?>" class="breadcrumb-item">Əsas səhifə</a>
            <span class="breadcrumb-separator">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M6 12l4-4-4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="breadcrumb-item active"><?php echo e($category->name); ?></span>
        </div>
    </div>

    <!-- Category Hero -->
    <section class="category-hero">
        <div class="category-hero-bg"></div>
        <div class="container">
            <div class="category-hero-content">
                <div class="category-icon-large">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <defs>
                            <linearGradient id="categoryGradient<?php echo e($category->id); ?>" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:<?php echo e($category->color); ?>;stop-opacity:0.8" />
                                <stop offset="100%" style="stop-color:<?php echo e($category->color); ?>;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <circle cx="40" cy="40" r="38" fill="url(#categoryGradient<?php echo e($category->id); ?>)" opacity="0.2"/>
                        <path d="M40 20L45 35H60L48 45L53 60L40 50L27 60L32 45L20 35H35L40 20Z" fill="url(#categoryGradient<?php echo e($category->id); ?>)"/>
                    </svg>
                </div>
                <h1 class="category-title"><?php echo e($category->name); ?></h1>
                <?php if($category->description): ?>
                <p class="category-description"><?php echo e($category->description); ?></p>
                <?php endif; ?>
                <div class="category-stats">
                    <span class="category-stat">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H9v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                        <?php echo e($posts->total()); ?> xəbər
                    </span>
                    <span class="category-stat">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zM9 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L7 13v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H6V8h2c.55 0 1-.45 1-1V5h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                        <?php if($totalViews >= 1000000): ?>
                            <?php echo e(number_format($totalViews / 1000000, 1)); ?>M baxış
                        <?php elseif($totalViews >= 1000): ?>
                            <?php echo e(number_format($totalViews / 1000, 1)); ?>K baxış
                        <?php else: ?>
                            <?php echo e(number_format($totalViews)); ?> baxış
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Content -->
    <section class="section-category">
        <div class="container">
            <div class="category-layout">
                <!-- Main Content -->
                <div class="category-main">
                    <!-- Articles Grid -->
                    <div class="category-grid">
                        <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <article class="category-card">
                            <a href="<?php echo e($post->url); ?>" class="category-card-image">
                                <?php if($post->featured_image_thumb): ?>
                                    <img src="<?php echo e($post->featured_image_thumb); ?>" alt="<?php echo e($post->title); ?>" loading="lazy">
                                <?php else: ?>
                                    <img src="/images/placeholder.jpg" alt="<?php echo e($post->title); ?>" loading="lazy">
                                <?php endif; ?>

                                <?php if($post->hasMedia('gallery')): ?>
                                <div class="gallery-icon">
                                    <svg width="32" height="32" viewBox="0 0 32 32">
                                        <rect width="32" height="32" rx="8" fill="url(#galleryGradient)"/>
                                        <path d="M24 10H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V12c0-1.1-.9-2-2-2zm-14 12l-2-2.5 2-2 1.5 1.5 4.5-6 5 6.5v3H10z" fill="white"/>
                                        <circle cx="11" cy="15" r="1.5" fill="white"/>
                                    </svg>
                                </div>
                                <?php endif; ?>

                                <span class="category-badge" data-category-id="<?php echo e($category->id); ?>" style="background-color: <?php echo e($category->color); ?>;">
                                    <?php echo e($category->name); ?>

                                </span>
                            </a>
                            <div class="category-card-content">
                                <h3 class="category-card-title">
                                    <a href="<?php echo e($post->url); ?>"><?php echo e($post->title); ?></a>
                                </h3>
                                <?php if($post->author): ?>
                                <div class="news-author">
                                    <img src="<?php echo e($post->author->avatar_thumb); ?>" alt="<?php echo e($post->author->name); ?>" class="author-avatar" loading="lazy">
                                    <div class="author-info">
                                        <span class="author-name"><?php echo e($post->author->name); ?></span>
                                        <span class="publish-date"><?php echo e($post->published_at->translatedFormat('d F Y, H:i')); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="no-posts">
                            <p>Bu kateqoriyada hələ xəbər yoxdur.</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if($posts->hasPages()): ?>
                    <div class="pagination">
                        <?php if($posts->onFirstPage()): ?>
                            <button class="pagination-btn pagination-prev" disabled>‹ Əvvəlki</button>
                        <?php else: ?>
                            <a href="<?php echo e($posts->previousPageUrl()); ?>" class="pagination-btn pagination-prev">‹ Əvvəlki</a>
                        <?php endif; ?>

                        <div class="pagination-numbers">
                            <?php $__currentLoopData = range(1, $posts->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == 1 || $page == $posts->lastPage() || abs($page - $posts->currentPage()) <= 1): ?>
                                    <?php if($page == $posts->currentPage()): ?>
                                        <button class="pagination-num active"><?php echo e($page); ?></button>
                                    <?php else: ?>
                                        <a href="<?php echo e($posts->url($page)); ?>" class="pagination-num"><?php echo e($page); ?></a>
                                    <?php endif; ?>
                                <?php elseif($page == 2 && $posts->currentPage() > 3): ?>
                                    <span class="pagination-dots">...</span>
                                <?php elseif($page == $posts->lastPage() - 1 && $posts->currentPage() < $posts->lastPage() - 2): ?>
                                    <span class="pagination-dots">...</span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <?php if($posts->hasMorePages()): ?>
                            <a href="<?php echo e($posts->nextPageUrl()); ?>" class="pagination-btn pagination-next">Növbəti ›</a>
                        <?php else: ?>
                            <button class="pagination-btn pagination-next" disabled>Növbəti ›</button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/category.blade.php ENDPATH**/ ?>