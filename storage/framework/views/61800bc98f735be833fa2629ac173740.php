<?php
    $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'OLAY.az';
?>

<?php $__env->startSection('title', 'Axtarış nəticələri: ' . $query . ' - ' . $siteName); ?>

<?php $__env->startSection('seo'); ?>
    <?php
        $searchTitle = "Axtarış: {$query} - {$siteName}";
        $searchDescription = "\"{$query}\" üzrə axtarış nəticələri {$siteName}-da. {$posts->total()} nəticə tapıldı.";
        $searchKeywords = "{$query}, axtarış, xəbərlər, " . strtolower($siteName);
    ?>
    <?php if (isset($component)) { $__componentOriginal42da61123f891e63201d7be28f403427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42da61123f891e63201d7be28f403427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo','data' => ['title' => $searchTitle,'description' => $searchDescription,'keywords' => $searchKeywords,'ogType' => 'website','ogImage' => asset('images/logo-cropped.png'),'canonical' => route('search', ['q' => $query]),'robots' => 'noindex, follow']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($searchTitle),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($searchDescription),'keywords' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($searchKeywords),'ogType' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('website'),'ogImage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('images/logo-cropped.png')),'canonical' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('search', ['q' => $query])),'robots' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('noindex, follow')]); ?>
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
            ['name' => 'Axtarış', 'url' => route('search', ['q' => $query])]
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('schema'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'breadcrumb','breadcrumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['name' => 'Əsas səhifə', 'url' => route('home')],
            ['name' => 'Axtarış', 'url' => route('search', ['q' => $query])]
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

    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SearchResultsPage",
      "name": "Axtarış nəticələri: <?php echo e($query); ?>",
      "description": "<?php echo e($posts->total()); ?> nəticə tapıldı",
      "url": "<?php echo e(route('search', ['q' => $query])); ?>",
      "inLanguage": "az",
      "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "<?php echo e(config('app.url')); ?>/search?q={search_term_string}"
        },
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    
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
            <span class="breadcrumb-item active">Axtarış</span>
        </div>
    </div>

    <!-- Search Results Hero -->
    <section class="search-hero">
        <div class="container">
            <div class="search-hero-content">
                <div class="search-icon-large">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <defs>
                            <linearGradient id="searchGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#4f46e5;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#06b6d4;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <circle cx="40" cy="40" r="38" fill="url(#searchGradient)" opacity="0.2"/>
                        <circle cx="35" cy="35" r="15" stroke="url(#searchGradient)" stroke-width="4" fill="none"/>
                        <path d="M46 46L58 58" stroke="url(#searchGradient)" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </div>
                <h1 class="search-title">Axtarış Nəticələri</h1>
                <p class="search-query">"<span><?php echo e($query); ?></span>" üzrə tapılanlar</p>
                <div class="search-stats">
                    <span class="search-stat">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo e($posts->total()); ?> nəticə
                    </span>
                </div>
                <!-- Inline Search Form -->
                <form class="search-hero-form" action="<?php echo e(route('search')); ?>" method="GET">
                    <input type="text" name="q" class="search-hero-input" placeholder="Yeni axtarış..." value="<?php echo e($query); ?>" autocomplete="off">
                    <button type="submit" class="search-hero-submit">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                            <path d="M17 17L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Axtar
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Search Results Content -->
    <section class="section-search-results">
        <div class="container">
            <div class="search-layout">
                <!-- Main Content -->
                <div class="search-main">
                    <!-- Results Grid -->
                    <div class="search-results-grid">
                        <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <article class="search-result-card">
                            <a href="<?php echo e($post->url); ?>" class="search-result-image">
                                <?php if($post->featured_image_thumb): ?>
                                    <img src="<?php echo e($post->featured_image_thumb); ?>" alt="<?php echo e($post->title); ?>" loading="lazy">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('images/placeholder.jpg')); ?>" alt="<?php echo e($post->title); ?>" loading="lazy">
                                <?php endif; ?>
                                <?php if($post->main_category): ?>
                                <span class="category-badge" data-category-id="<?php echo e($post->main_category->id); ?>" style="background-color: <?php echo e($post->main_category->color); ?>;">
                                    <?php echo e($post->main_category->name); ?>

                                </span>
                                <?php endif; ?>
                            </a>
                            <div class="search-result-content">
                                <h3 class="search-result-title">
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
                        <div class="no-results">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                                <path d="M21 21l-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <h3>Heç bir nəticə tapılmadı</h3>
                            <p>Zəhmət olmasa başqa açar söz ilə yenidən cəhd edin</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if($posts->hasPages()): ?>
                    <div class="pagination">
                        <?php if($posts->onFirstPage()): ?>
                            <button class="pagination-btn pagination-prev" disabled>‹ Əvvəlki</button>
                        <?php else: ?>
                            <a href="<?php echo e($posts->appends(['q' => $query])->previousPageUrl()); ?>" class="pagination-btn pagination-prev">‹ Əvvəlki</a>
                        <?php endif; ?>

                        <div class="pagination-numbers">
                            <?php $__currentLoopData = range(1, $posts->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == 1 || $page == $posts->lastPage() || abs($page - $posts->currentPage()) <= 1): ?>
                                    <?php if($page == $posts->currentPage()): ?>
                                        <button class="pagination-num active"><?php echo e($page); ?></button>
                                    <?php else: ?>
                                        <a href="<?php echo e($posts->appends(['q' => $query])->url($page)); ?>" class="pagination-num"><?php echo e($page); ?></a>
                                    <?php endif; ?>
                                <?php elseif($page == 2 && $posts->currentPage() > 3): ?>
                                    <span class="pagination-dots">...</span>
                                <?php elseif($page == $posts->lastPage() - 1 && $posts->currentPage() < $posts->lastPage() - 2): ?>
                                    <span class="pagination-dots">...</span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <?php if($posts->hasMorePages()): ?>
                            <a href="<?php echo e($posts->appends(['q' => $query])->nextPageUrl()); ?>" class="pagination-btn pagination-next">Növbəti ›</a>
                        <?php else: ?>
                            <button class="pagination-btn pagination-next" disabled>Növbəti ›</button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <aside class="search-sidebar">
                    <!-- Related Searches -->
                    <div class="sidebar-block related-searches-block">
                        <h3 class="sidebar-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9.5 3A6.5 6.5 0 0 1 16 9.5c0 1.61-.59 3.09-1.56 4.23l.27.27h.79l5 5-1.5 1.5-5-5v-.79l-.27-.27A6.516 6.516 0 0 1 9.5 16a6.5 6.5 0 1 1 0-13zm0 2C7 5 5 7 5 9.5S7 14 9.5 14 14 12 14 9.5 12 5 9.5 5z"/>
                            </svg>
                            Ən çox axtarılan
                        </h3>
                        <div class="related-searches">
                            <?php
                                $topQueries = \App\Models\SearchQuery::getTopQueries(5);
                            ?>
                            <?php $__currentLoopData = $topQueries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topQuery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('search', ['q' => $topQuery])); ?>" class="related-search-item"><?php echo e($topQuery); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Trending Section -->
                    <div class="sidebar-block trending-block">
                        <h3 class="sidebar-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
                            </svg>
                            Günün trendi
                        </h3>
                        <div class="trending-list">
                            <?php
                                $trendingPosts = \App\Models\Post::published()
                                    ->where('show_in_important_today', true)
                                    ->with(['category'])
                                    ->latest('published_at')
                                    ->take(\App\Models\Setting::get('trending_posts_count', 5))
                                    ->get();
                            ?>
                            <?php $__currentLoopData = $trendingPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trendingPost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($trendingPost->url); ?>" class="trending-item">
                                <span class="trending-number">#<?php echo e($index + 1); ?></span>
                                <div class="trending-content">
                                    <h4 class="trending-title"><?php echo e($trendingPost->title); ?></h4>
                                    <span class="trending-views"><?php echo e(number_format($trendingPost->views)); ?> baxış</span>
                                </div>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="sidebar-block categories-block">
                        <h3 class="sidebar-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
                            </svg>
                            Kateqoriyalar
                        </h3>
                        <div class="sidebar-categories">
                            <?php
                                $sidebarCategories = \App\Models\Category::where('is_active', true)
                                    ->where('show_in_menu', true)
                                    ->withCount(['posts' => function($query) {
                                        $query->published();
                                    }])
                                    ->orderBy('order')
                                    ->get();
                            ?>
                            <?php $__currentLoopData = $sidebarCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('category', $sidebarCategory->slug)); ?>" class="sidebar-category">
                                <span class="sidebar-category-name"><?php echo e($sidebarCategory->name); ?></span>
                                <span class="sidebar-category-count"><?php echo e($sidebarCategory->posts_count); ?></span>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/search.blade.php ENDPATH**/ ?>