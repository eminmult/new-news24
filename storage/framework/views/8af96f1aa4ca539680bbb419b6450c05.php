<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', $mainInfo?->site_name ?? 'OLAY.az'); ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(asset('assets/favicon/apple-icon-57x57.png')); ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo e(asset('assets/favicon/apple-icon-60x60.png')); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo e(asset('assets/favicon/apple-icon-72x72.png')); ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('assets/favicon/apple-icon-76x76.png')); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e(asset('assets/favicon/apple-icon-114x114.png')); ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo e(asset('assets/favicon/apple-icon-120x120.png')); ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo e(asset('assets/favicon/apple-icon-144x144.png')); ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(asset('assets/favicon/apple-icon-152x152.png')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('assets/favicon/apple-icon-180x180.png')); ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo e(asset('assets/favicon/android-icon-192x192.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('assets/favicon/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo e(asset('assets/favicon/favicon-96x96.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('assets/favicon/favicon-16x16.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('assets/favicon/manifest.json')); ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo e(asset('assets/favicon/ms-icon-144x144.png')); ?>">
    <meta name="theme-color" content="#ffffff">

    
    <?php echo $__env->yieldContent('seo'); ?>

    
    <?php echo $__env->yieldContent('schema'); ?>

    <link rel="stylesheet" href="<?php echo e(asset('css/design.css?v=16.3.6')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/custom.css?v=1.9.0')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/responsive.css?v=2.9.0')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo $__env->yieldContent('styles'); ?>

    
    <?php if(config_value('GOOGLE_ANALYTICS')): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(config_value('GOOGLE_ANALYTICS')); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo e(config_value('GOOGLE_ANALYTICS')); ?>');
    </script>
    <?php endif; ?>

    
    <?php if(config_value('YANDEX_METRIKA')): ?>
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(<?php echo e(config_value('YANDEX_METRIKA')); ?>, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/<?php echo e(config_value('YANDEX_METRIKA')); ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <?php endif; ?>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-top">
                <div class="social-links">
                    <?php if($socialLinks['instagram']): ?>
                    <a href="<?php echo e($socialLinks['instagram']); ?>" class="social-link" aria-label="Instagram" target="_blank" rel="noopener">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if($socialLinks['facebook']): ?>
                    <a href="<?php echo e($socialLinks['facebook']); ?>" class="social-link" aria-label="Facebook" target="_blank" rel="noopener">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if($socialLinks['youtube']): ?>
                    <a href="<?php echo e($socialLinks['youtube']); ?>" class="social-link" aria-label="YouTube" target="_blank" rel="noopener">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if($socialLinks['tiktok']): ?>
                    <a href="<?php echo e($socialLinks['tiktok']); ?>" class="social-link" aria-label="TikTok" target="_blank" rel="noopener">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if($socialLinks['telegram']): ?>
                    <a href="<?php echo e($socialLinks['telegram']); ?>" class="social-link" aria-label="Telegram" target="_blank" rel="noopener">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if($socialLinks['phone']): ?>
                    <a href="tel:<?php echo e($socialLinks['phone']); ?>" class="social-link phone-link" aria-label="Telefon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="header-content">
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Menyu" onclick="document.getElementById('mobileNav').classList.add('active'); this.classList.add('active'); document.body.style.position='fixed'; document.body.style.width='100%'; document.body.style.overflow='hidden'; document.getElementById('mobileMenuClose').style.display='flex';">
                    <span class="burger-line"></span>
                    <span class="burger-line"></span>
                    <span class="burger-line"></span>
                </button>
                <a href="<?php echo e(route('home')); ?>" class="logo">
                    <picture>
                        <source srcset="<?php echo e(asset('images/logo-cropped.webp')); ?>" type="image/webp">
                        <img src="<?php echo e(asset('images/logo-cropped.png')); ?>" alt="<?php echo e($mainInfo?->site_name ?? 'OLAY.az'); ?>" width="205" height="60">
                    </picture>
                </a>
                <nav class="nav" id="mobileNav">
                    <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Bağla" onclick="document.getElementById('mobileNav').classList.remove('active'); document.getElementById('mobileMenuToggle').classList.remove('active'); document.body.style.position=''; document.body.style.width=''; document.body.style.overflow=''; this.style.display='none';">&times;</button>

                    <!-- Scrollable content wrapper -->
                    <div class="nav-content-wrapper">
                        <!-- Logo in mobile menu -->
                        <a href="<?php echo e(route('home')); ?>" class="mobile-nav-logo">
                            <picture>
                                <source srcset="<?php echo e(asset('images/logo-cropped.webp')); ?>" type="image/webp">
                                <img src="<?php echo e(asset('images/logo-cropped.png')); ?>" alt="<?php echo e($mainInfo?->site_name ?? 'OLAY.az'); ?>" width="205" height="60">
                            </picture>
                        </a>

                        <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('category', $category->slug)); ?>" class="nav-link <?php echo e(request()->is($category->slug) ? 'active' : ''); ?>"><?php echo e($category->name); ?></a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Social links in mobile menu - Fixed at bottom -->
                    <div class="mobile-nav-social">
                        <?php if($socialLinks['instagram']): ?>
                        <a href="<?php echo e($socialLinks['instagram']); ?>" class="social-link" aria-label="Instagram" target="_blank" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <?php if($socialLinks['facebook']): ?>
                        <a href="<?php echo e($socialLinks['facebook']); ?>" class="social-link" aria-label="Facebook" target="_blank" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <?php if($socialLinks['youtube']): ?>
                        <a href="<?php echo e($socialLinks['youtube']); ?>" class="social-link" aria-label="YouTube" target="_blank" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <?php if($socialLinks['tiktok']): ?>
                        <a href="<?php echo e($socialLinks['tiktok']); ?>" class="social-link" aria-label="TikTok" target="_blank" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <?php if($socialLinks['telegram']): ?>
                        <a href="<?php echo e($socialLinks['telegram']); ?>" class="social-link" aria-label="Telegram" target="_blank" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <?php if($socialLinks['phone']): ?>
                        <a href="tel:<?php echo e($socialLinks['phone']); ?>" class="social-link phone-link" aria-label="Telefon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </nav>
                <div class="header-actions">
                    <button class="search-btn" aria-label="Axtarış">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <circle cx="9" cy="9" r="7" stroke="currentColor" stroke-width="2"/>
                            <path d="M14 14L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Search Modal -->
    <div class="search-modal" id="searchModal">
        <div class="search-modal-content">
            <button class="search-modal-close" id="searchModalClose" aria-label="Bağla">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            <div class="search-modal-body">
                <h2>Axtar</h2>
                <form class="search-form" id="searchForm" action="<?php echo e(route('search')); ?>" method="GET">
                    <input type="text" class="search-input" name="q" placeholder="Axtarış üçün mətn daxil edin..." autocomplete="off">
                    <button type="submit" class="search-submit" aria-label="Axtar">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                            <path d="M17 17L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php echo $__env->yieldContent('content'); ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="<?php echo e(route('about')); ?>">Haqqımızda</a>
                    <a href="<?php echo e(route('contact')); ?>">Bizimlə Əlaqə</a>
                </div>
                <div class="footer-copy">
                    <p>&copy; <?php echo e(date('Y')); ?> <?php echo e($mainInfo?->site_name ?? 'OLAY.az'); ?> - Bütün hüquqlar qorunur</p>
                    <p class="footer-credit">Created by GASIMOV.AZ</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- SVG Gradients -->
    <svg width="0" height="0" style="position: absolute;">
        <defs>
            <linearGradient id="playGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#fc0067;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#ab21f4;stop-opacity:1" />
            </linearGradient>
            <linearGradient id="galleryGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#35d388;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#ffd525;stop-opacity:1" />
            </linearGradient>
        </defs>
    </svg>

    <script src="<?php echo e(asset('js/main.js?v=' . time())); ?>"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>