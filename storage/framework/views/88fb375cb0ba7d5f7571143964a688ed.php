

<?php if($type === 'website'): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "OLAY.az",
  "alternateName": "Olay",
  "url": "<?php echo e(config('app.url')); ?>",
  "description": "Azərbaycanın ən son xəbərləri, analitika və eksklüziv materiallar",
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
<?php endif; ?>

<?php if($type === 'organization'): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
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
  "sameAs": [
    <?php
      $socialLinksArray = array_filter([
        $socialLinks['instagram'] ?? null,
        $socialLinks['facebook'] ?? null,
        $socialLinks['youtube'] ?? null,
        $socialLinks['telegram'] ?? null,
        $socialLinks['tiktok'] ?? null,
      ]);
    ?>
    <?php echo '"' . implode('","', $socialLinksArray) . '"'; ?>

  ],
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "<?php echo e($socialLinks['phone'] ?? ''); ?>",
    "contactType": "Customer Service",
    "areaServed": "AZ",
    "availableLanguage": ["Azerbaijani", "Russian"]
  }
}
</script>
<?php endif; ?>

<?php if($type === 'newsarticle' && isset($article)): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "<?php echo e($article->title); ?>",
  "description": "<?php echo e($article->meta_description); ?>",
  "image": {
    "@type": "ImageObject",
    "url": "<?php echo e($article->featured_image ?? asset('images/placeholder.jpg')); ?>",
    "width": 1200,
    "height": 630
  },
  "datePublished": "<?php echo e($article->published_at->toIso8601String()); ?>",
  "dateModified": "<?php echo e($article->updated_at->toIso8601String()); ?>",
  "author": {
    "@type": "Person",
    "name": "<?php echo e($article->author->name ?? 'OLAY.az'); ?>",
    <?php if($article->author): ?>
    "url": "<?php echo e(route('search', ['q' => $article->author->name])); ?>"
    <?php else: ?>
    "url": "<?php echo e(route('home')); ?>"
    <?php endif; ?>
  },
  "publisher": {
    "@type": "Organization",
    "name": "OLAY.az",
    "logo": {
      "@type": "ImageObject",
      "url": "<?php echo e(asset('images/logo-cropped.png')); ?>",
      "width": 200,
      "height": 60
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php echo e(url()->current()); ?>"
  },
  "articleSection": "<?php echo e($article->category->name ?? 'Xəbərlər'); ?>",
  "keywords": "<?php echo e($article->meta_keywords); ?>",
  "wordCount": <?php echo e(count(array_filter(preg_split('/\s+/', strip_tags($article->content))))); ?>,
  "inLanguage": "az"
}
</script>
<?php endif; ?>

<?php if($type === 'breadcrumb' && isset($breadcrumbs)): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    {
      "@type": "ListItem",
      "position": <?php echo e($index + 1); ?>,
      "name": "<?php echo e($crumb['name']); ?>",
      "item": "<?php echo e($crumb['url']); ?>"
    }<?php if(!$loop->last): ?>,<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  ]
}
</script>
<?php endif; ?>

<?php if($type === 'itemlist' && isset($items)): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    {
      "@type": "ListItem",
      "position": <?php echo e($index + 1); ?>,
      "url": "<?php echo e($item->url); ?>",
      "name": "<?php echo e($item->title); ?>"
    }<?php if(!$loop->last): ?>,<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  ]
}
</script>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/components/schema.blade.php ENDPATH**/ ?>