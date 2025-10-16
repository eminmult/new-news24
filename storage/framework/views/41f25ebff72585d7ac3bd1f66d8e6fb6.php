<?php
    $mainInfo = \App\Models\MainInfo::getInstance();
    $siteName = $mainInfo?->site_name ?? 'News24.az';
?>

<?php $__env->startSection('title', $siteName . ' - Əsas səhifə'); ?>

<?php $__env->startSection('seo'); ?>
    <?php if (isset($component)) { $__componentOriginal42da61123f891e63201d7be28f403427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42da61123f891e63201d7be28f403427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo','data' => ['title' => ($mainInfo?->meta_title ?? $siteName) . ' - Azərbaycanın aparıcı xəbər portalı','description' => $mainInfo?->meta_description ?? 'Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar. Siyasət, iqtisadiyyat, idman, mədəniyyət və daha çox.','keywords' => $mainInfo?->meta_keywords ?? 'xəbərlər, azərbaycan xəbərləri, son xəbərlər, günün xəbərləri, news24.az, siyasət, iqtisadiyyat, idman','ogType' => 'website','ogImage' => asset('images/logo-cropped.png'),'canonical' => route('home')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($mainInfo?->meta_title ?? $siteName) . ' - Azərbaycanın aparıcı xəbər portalı'),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mainInfo?->meta_description ?? 'Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar. Siyasət, iqtisadiyyat, idman, mədəniyyət və daha çox.'),'keywords' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mainInfo?->meta_keywords ?? 'xəbərlər, azərbaycan xəbərləri, son xəbərlər, günün xəbərləri, news24.az, siyasət, iqtisadiyyat, idman'),'ogType' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('website'),'ogImage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('images/logo-cropped.png')),'canonical' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('home'))]); ?>
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
    <!-- Hero Slider -->
    <?php if($sliderPosts->isNotEmpty()): ?>
    <section class="hero-slider">
        <div class="slider-container">
            <?php $__currentLoopData = $sliderPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="slide <?php echo e($loop->first ? 'active' : ''); ?>">
                <?php
                    $firstMedia = $post->getMedia('post-gallery')->first();
                    $imageUrl = $firstMedia ? $firstMedia->getUrl('webp') : asset('images/placeholder.jpg');
                ?>
                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($post->title); ?>">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <div class="container">
                        <?php if($post->main_category): ?>
                        <span class="category-badge" data-category-id="<?php echo e($post->main_category->id); ?>" style="background-color: <?php echo e($post->main_category->color); ?>;"><?php echo e($post->main_category->name); ?></span>
                        <?php endif; ?>
                        <h1 class="slide-title"><a href="<?php echo e($post->url); ?>"><?php echo e($post->title); ?></a></h1>
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
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Slider Navigation -->
        <button class="slider-arrow slider-prev" aria-label="Əvvəlki slayd">‹</button>
        <button class="slider-arrow slider-next" aria-label="Növbəti slayd">›</button>

        <!-- Slider Dots -->
        <div class="slider-dots">
            <?php $__currentLoopData = $sliderPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="dot <?php echo e($loop->first ? 'active' : ''); ?>" data-slide="<?php echo e($loop->index); ?>"></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Today's Important News Section -->
    <?php if($importantPosts->isNotEmpty()): ?>
    <section class="section-highlights">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">⭐ BUGÜNÜN ƏN ÖNƏMLİ XƏBƏRLƏRİ</h2>
            </div>

            <div class="today-grid">
                <?php
                    $mainPost = $importantPosts->first();
                    $smallPosts = $importantPosts->slice(1, 3);
                ?>

                
                <?php if($mainPost): ?>
                <article class="today-card-large">
                    <div class="today-image">
                        <?php
                            $mainMedia = $mainPost->getMedia('post-gallery')->first();
                            $mainImageUrl = $mainMedia ? $mainMedia->getUrl('medium') : '/images/placeholder.jpg';
                        ?>
                        <img src="<?php echo e($mainImageUrl); ?>" alt="<?php echo e($mainPost->title); ?>" loading="lazy">
                        <?php if($mainPost->main_category): ?>
                        <span class="category-badge" data-category-id="<?php echo e($mainPost->main_category->id); ?>" style="background-color: <?php echo e($mainPost->main_category->color); ?>;">
                            <?php echo e($mainPost->main_category->name); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="today-content">
                        <h2 class="today-title-large">
                            <a href="<?php echo e($mainPost->url); ?>"><?php echo e($mainPost->title); ?></a>
                        </h2>
                        <?php if($mainPost->author): ?>
                        <div class="news-author">
                            <img src="<?php echo e($mainPost->author->avatar_thumb); ?>"
                                 alt="<?php echo e($mainPost->author->name); ?>"
                                 class="author-avatar" loading="lazy">
                            <div class="author-info">
                                <span class="author-name"><?php echo e($mainPost->author->name); ?></span>
                                <span class="publish-date"><?php echo e($mainPost->published_at->translatedFormat('d F Y, H:i')); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endif; ?>

                
                <?php if($smallPosts->isNotEmpty()): ?>
                <div class="today-grid-small">
                    <?php $__currentLoopData = $smallPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="today-card-small">
                        <div class="today-image-small">
                            <?php if($post->featured_image_thumb): ?>
                                <img src="<?php echo e($post->featured_image_thumb); ?>"
                                     alt="<?php echo e($post->title); ?>" loading="lazy">
                            <?php else: ?>
                                <img src="/images/placeholder.jpg" alt="<?php echo e($post->title); ?>" loading="lazy">
                            <?php endif; ?>
                            <?php if($post->main_category): ?>
                            <span class="category-badge" data-category-id="<?php echo e($post->main_category->id); ?>" style="background-color: <?php echo e($post->main_category->color); ?>;">
                                <?php echo e($post->main_category->name); ?>

                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="today-content-small">
                            <h3 class="today-title-small">
                                <a href="<?php echo e($post->url); ?>"><?php echo e($post->title); ?></a>
                            </h3>
                            <?php if($post->author): ?>
                            <div class="news-author">
                                <img src="<?php echo e($post->author->avatar_thumb); ?>"
                                     alt="<?php echo e($post->author->name); ?>"
                                     class="author-avatar" loading="lazy">
                                <div class="author-info">
                                    <span class="author-name"><?php echo e($post->author->name); ?></span>
                                    <span class="publish-date"><?php echo e($post->published_at->translatedFormat('d F Y')); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Media Section (FOTO-VIDEO) -->
    <?php if($mediaPosts->isNotEmpty()): ?>
    <section class="section-media">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">📸 FOTO-VİDEO</h2>
            </div>

            <div class="media-showcase">
                <?php
                    // Первый пост - featured (большая карточка)
                    $featuredPost = $mediaPosts->first();
                    // Остальные - маленькие карточки
                    $smallMediaPosts = $mediaPosts->slice(1, 4);
                ?>

                <!-- Main Featured Video/Photo -->
                <?php if($featuredPost): ?>
                <article class="media-featured">
                    <div class="media-featured-image">
                        <?php
                            $featuredMedia = $featuredPost->getMedia('post-gallery')->first();
                            $featuredImageUrl = $featuredMedia ? $featuredMedia->getUrl('medium') : '/images/placeholder.jpg';
                        ?>
                        <img src="<?php echo e($featuredImageUrl); ?>" alt="<?php echo e($featuredPost->title); ?>" loading="lazy">
                        <div class="featured-gradient"></div>

                        <?php if($featuredPost->types->contains('slug', 'video')): ?>
                        <div class="featured-play">
                            <svg width="80" height="80" viewBox="0 0 80 80">
                                <circle cx="40" cy="40" r="40" fill="white" opacity="0.95"/>
                                <path d="M32 24v32l28-16z" fill="#ef4444"/>
                            </svg>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="media-featured-content">
                        <span class="featured-tag">
                            <?php if($featuredPost->types->contains('slug', 'video')): ?>
                                VİDEO
                            <?php else: ?>
                                FOTO
                            <?php endif; ?>
                        </span>
                        <h3 class="featured-title">
                            <a href="<?php echo e($featuredPost->url); ?>"><?php echo e($featuredPost->title); ?></a>
                        </h3>
                        <?php if($featuredPost->author): ?>
                        <div class="featured-author">
                            <img src="<?php echo e($featuredPost->author->avatar_thumb); ?>" alt="<?php echo e($featuredPost->author->name); ?>" loading="lazy">
                            <div>
                                <span class="featured-author-name"><?php echo e($featuredPost->author->name); ?></span>
                                <span class="featured-date"><?php echo e($featuredPost->published_at->translatedFormat('d F Y')); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endif; ?>

                <!-- Photos/Videos Grid -->
                <?php if($smallMediaPosts->isNotEmpty()): ?>
                <div class="media-photos">
                    <?php $__currentLoopData = $smallMediaPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mediaPost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="photo-item">
                        <div class="photo-wrapper">
                            <?php if($mediaPost->featured_image_thumb): ?>
                                <img src="<?php echo e($mediaPost->featured_image_thumb); ?>" alt="<?php echo e($mediaPost->title); ?>" loading="lazy">
                            <?php else: ?>
                                <img src="/images/placeholder.jpg" alt="<?php echo e($mediaPost->title); ?>" loading="lazy">
                            <?php endif; ?>
                            <div class="photo-gradient"></div>

                            <?php if($mediaPost->types->contains('slug', 'video')): ?>
                            <div class="photo-icon">
                                <svg width="40" height="40" viewBox="0 0 40 40">
                                    <circle cx="20" cy="20" r="20" fill="white" opacity="0.95"/>
                                    <path d="M16 12v16l14-8z" fill="#ef4444"/>
                                </svg>
                            </div>
                            <?php else: ?>
                            <div class="photo-icon">
                                <svg width="40" height="40" viewBox="0 0 40 40">
                                    <circle cx="20" cy="20" r="20" fill="white" opacity="0.95"/>
                                    <path d="M26 14H14c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V16c0-1.1-.9-2-2-2zm-10 11l-2-2.5 2-2 1.5 1.5 4-5 5 6v3H16z" fill="#ec4899"/>
                                </svg>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="photo-content">
                            <h4 class="photo-title">
                                <a href="<?php echo e($mediaPost->url); ?>"><?php echo e($mediaPost->title); ?></a>
                            </h4>
                            <?php if($mediaPost->author): ?>
                            <div class="photo-author">
                                <img src="<?php echo e($mediaPost->author->avatar_thumb); ?>" alt="<?php echo e($mediaPost->author->name); ?>" loading="lazy">
                                <span class="photo-author-name"><?php echo e($mediaPost->author->name); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- All News Section -->
    <section class="section-all-news">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">📰 BÜTÜN XƏBƏRLƏR</h2>
            </div>
            <div class="feed-grid">
                <?php $__currentLoopData = $latestPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="feed-card">
                    <div class="feed-image">
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

                        <?php if($post->main_category): ?>
                        <span class="category-badge" data-category-id="<?php echo e($post->main_category->id); ?>" style="background-color: <?php echo e($post->main_category->color); ?>;">
                            <?php echo e($post->main_category->name); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="feed-content">
                        <h3 class="feed-title">
                            <a href="<?php echo e($post->url); ?>"><?php echo e($post->title); ?></a>
                        </h3>
                        <?php if($post->author): ?>
                        <div class="news-author">
                            <img src="<?php echo e($post->author->avatar_thumb); ?>" alt="<?php echo e($post->author->name); ?>" class="author-avatar" loading="lazy">
                            <div class="author-info">
                                <span class="author-name"><?php echo e($post->author->name); ?></span>
                                <span class="publish-date"><?php echo e($post->published_at->translatedFormat('d F Y')); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <?php if($latestPosts->hasPages()): ?>
            <div class="pagination">
                <?php if($latestPosts->onFirstPage()): ?>
                    <button class="pagination-btn pagination-prev" disabled>‹ Əvvəlki</button>
                <?php else: ?>
                    <a href="<?php echo e($latestPosts->previousPageUrl()); ?>" class="pagination-btn pagination-prev">‹ Əvvəlki</a>
                <?php endif; ?>

                <div class="pagination-numbers">
                    <?php $__currentLoopData = range(1, $latestPosts->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == 1 || $page == $latestPosts->lastPage() || abs($page - $latestPosts->currentPage()) <= 1): ?>
                            <?php if($page == $latestPosts->currentPage()): ?>
                                <button class="pagination-num active"><?php echo e($page); ?></button>
                            <?php else: ?>
                                <a href="<?php echo e($latestPosts->url($page)); ?>" class="pagination-num"><?php echo e($page); ?></a>
                            <?php endif; ?>
                        <?php elseif($page == 2 && $latestPosts->currentPage() > 3): ?>
                            <span class="pagination-dots">...</span>
                        <?php elseif($page == $latestPosts->lastPage() - 1 && $latestPosts->currentPage() < $latestPosts->lastPage() - 2): ?>
                            <span class="pagination-dots">...</span>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($latestPosts->hasMorePages()): ?>
                    <a href="<?php echo e($latestPosts->nextPageUrl()); ?>" class="pagination-btn pagination-next">Növbəti ›</a>
                <?php else: ?>
                    <button class="pagination-btn pagination-next" disabled>Növbəti ›</button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script src="/js/slider.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/home.blade.php ENDPATH**/ ?>