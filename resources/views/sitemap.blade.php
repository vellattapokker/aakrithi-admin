{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $url): ?>
    <url>
        <loc><?php echo e($url['loc']); ?></loc>
<?php if (isset($url['lastmod'])): ?>
        <lastmod><?php echo e($url['lastmod']); ?></lastmod>
<?php endif; ?>
        <priority><?php echo e($url['priority']); ?></priority>
        <changefreq>weekly</changefreq>
    </url>
<?php endforeach; ?>
</urlset>
