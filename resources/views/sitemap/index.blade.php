<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://hotdeal.id/</loc>
        <lastmod>2022-03-15T10:09:23+00:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>https://hotdeal.id/about-us</loc>
        <lastmod>2022-03-15T10:09:23+00:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://hotdeal.id/help-center</loc>
        <lastmod>2022-03-15T10:09:23+00:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://hotdeal.id/privacy-policy</loc>
        <lastmod>2022-03-15T10:09:23+00:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    {{-- Product List --}}
    @foreach ($posts as $post)
        <url>
            <loc>https://hotdeal.id/product-detail/{{ $post->slug }}</loc>
            <lastmod>{{ $post->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>