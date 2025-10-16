
<?php
    $mainInfo = \App\Models\MainInfo::getInstance();
    $siteName = $mainInfo?->site_name ?? 'News24.az';
    $siteDescription = $mainInfo?->meta_description ?? 'News24.az - Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar';
    $siteKeywords = $mainInfo?->meta_keywords ?? 'xəbərlər, azərbaycan xəbərləri, son xəbərlər, günün xəbərləri, news24.az';
?>


<meta name="description" content="<?php echo e($description ?? $siteDescription); ?>">
<meta name="keywords" content="<?php echo e($keywords ?? $siteKeywords); ?>">
<meta name="author" content="<?php echo e($siteName); ?>">
<meta name="robots" content="<?php echo e($robots ?? 'index, follow'); ?>">
<meta name="googlebot" content="<?php echo e($robots ?? 'index, follow'); ?>">
<link rel="canonical" href="<?php echo e($canonical ?? url()->current()); ?>">


<meta property="og:locale" content="az_AZ">
<meta property="og:type" content="<?php echo e($ogType ?? 'website'); ?>">
<meta property="og:title" content="<?php echo e($ogTitle ?? $title ?? $siteName); ?>">
<meta property="og:description" content="<?php echo e($ogDescription ?? $description ?? $siteDescription); ?>">
<meta property="og:url" content="<?php echo e($ogUrl ?? url()->current()); ?>">
<meta property="og:site_name" content="<?php echo e($siteName); ?>">
<?php if(isset($ogImage)): ?>
<meta property="og:image" content="<?php echo e($ogImage); ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:type" content="image/jpeg">
<?php endif; ?>
<?php if(isset($publishedTime)): ?>
<meta property="article:published_time" content="<?php echo e($publishedTime); ?>">
<?php endif; ?>
<?php if(isset($modifiedTime)): ?>
<meta property="article:modified_time" content="<?php echo e($modifiedTime); ?>">
<?php endif; ?>
<?php if(isset($section)): ?>
<meta property="article:section" content="<?php echo e($section); ?>">
<?php endif; ?>
<?php if(isset($tags)): ?>
<?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<meta property="article:tag" content="<?php echo e($tag); ?>">
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>


<meta name="twitter:card" content="<?php echo e($twitterCard ?? 'summary_large_image'); ?>">
<meta name="twitter:title" content="<?php echo e($twitterTitle ?? $ogTitle ?? $title ?? $siteName); ?>">
<meta name="twitter:description" content="<?php echo e($twitterDescription ?? $ogDescription ?? $description ?? $siteDescription); ?>">
<?php if(isset($ogImage)): ?>
<meta name="twitter:image" content="<?php echo e($ogImage); ?>">
<?php endif; ?>
<?php if(isset($twitterSite)): ?>
<meta name="twitter:site" content="<?php echo e($twitterSite); ?>">
<?php endif; ?>
<?php if(isset($twitterCreator)): ?>
<meta name="twitter:creator" content="<?php echo e($twitterCreator); ?>">
<?php endif; ?>


<meta name="language" content="Azerbaijani">
<meta http-equiv="content-language" content="az">
<meta name="geo.region" content="AZ">
<meta name="geo.placename" content="Azerbaijan">
<?php /**PATH /var/www/html/resources/views/components/seo.blade.php ENDPATH**/ ?>