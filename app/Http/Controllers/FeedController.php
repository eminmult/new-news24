<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    /**
     * RSS Feed - Son 50 haber
     */
    public function rss(): Response
    {
        $posts = Post::published()
            ->with(['categories', 'author'])
            ->latest('published_at')
            ->take(50)
            ->get();

        $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'News24.az';
        $siteDescription = \App\Models\MainInfo::getInstance()?->meta_description ?? 'Azərbaycanın ən son xəbərləri';
        $siteUrl = config('app.url');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:media="http://search.yahoo.com/mrss/">';
        $xml .= '<channel>';
        $xml .= '<title>' . htmlspecialchars($siteName) . '</title>';
        $xml .= '<link>' . htmlspecialchars($siteUrl) . '</link>';
        $xml .= '<description>' . htmlspecialchars($siteDescription) . '</description>';
        $xml .= '<language>az</language>';
        $xml .= '<lastBuildDate>' . now()->toRssString() . '</lastBuildDate>';
        $xml .= '<pubDate>' . now()->toRssString() . '</pubDate>';
        $xml .= '<ttl>60</ttl>';
        $xml .= '<atom:link href="' . htmlspecialchars($siteUrl . '/feed/rss') . '" rel="self" type="application/rss+xml" />';
        $xml .= '<image>';
        $xml .= '<url>' . htmlspecialchars($siteUrl . '/images/logo-cropped.png') . '</url>';
        $xml .= '<title>' . htmlspecialchars($siteName) . '</title>';
        $xml .= '<link>' . htmlspecialchars($siteUrl) . '</link>';
        $xml .= '</image>';

        foreach ($posts as $post) {
            $xml .= '<item>';
            $xml .= '<title>' . htmlspecialchars($post->title) . '</title>';
            $xml .= '<link>' . htmlspecialchars($post->url) . '</link>';
            $xml .= '<guid isPermaLink="true">' . htmlspecialchars($post->url) . '</guid>';
            $xml .= '<description><![CDATA[' . htmlspecialchars($post->meta_description ?? strip_tags($post->excerpt ?? '')) . ']]></description>';
            $xml .= '<content:encoded><![CDATA[' . $post->content . ']]></content:encoded>';
            $xml .= '<pubDate>' . $post->published_at->toRssString() . '</pubDate>';
            $xml .= '<dc:creator>' . htmlspecialchars($post->author->name ?? 'News24.az') . '</dc:creator>';
            
            if ($post->main_category) {
                $xml .= '<category>' . htmlspecialchars($post->main_category->name) . '</category>';
            }
            
            if ($post->featured_image) {
                $xml .= '<media:content url="' . htmlspecialchars($post->featured_image) . '" type="image/jpeg" medium="image">';
                $xml .= '<media:title>' . htmlspecialchars($post->title) . '</media:title>';
                $xml .= '</media:content>';
            }
            
            $xml .= '</item>';
        }

        $xml .= '</channel>';
        $xml .= '</rss>';

        return response($xml, 200)
            ->header('Content-Type', 'application/rss+xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=300');
    }

    /**
     * Atom Feed - Son 50 haber
     */
    public function atom(): Response
    {
        $posts = Post::published()
            ->with(['categories', 'author'])
            ->latest('published_at')
            ->take(50)
            ->get();

        $siteName = \App\Models\MainInfo::getInstance()?->site_name ?? 'News24.az';
        $siteDescription = \App\Models\MainInfo::getInstance()?->meta_description ?? 'Azərbaycanın ən son xəbərləri';
        $siteUrl = config('app.url');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">';
        $xml .= '<title>' . htmlspecialchars($siteName) . '</title>';
        $xml .= '<subtitle>' . htmlspecialchars($siteDescription) . '</subtitle>';
        $xml .= '<link href="' . htmlspecialchars($siteUrl) . '" rel="alternate" />';
        $xml .= '<link href="' . htmlspecialchars($siteUrl . '/feed/atom') . '" rel="self" />';
        $xml .= '<id>' . htmlspecialchars($siteUrl) . '</id>';
        $xml .= '<updated>' . now()->toAtomString() . '</updated>';
        $xml .= '<logo>' . htmlspecialchars($siteUrl . '/images/logo-cropped.png') . '</logo>';

        foreach ($posts as $post) {
            $xml .= '<entry>';
            $xml .= '<title>' . htmlspecialchars($post->title) . '</title>';
            $xml .= '<link href="' . htmlspecialchars($post->url) . '" />';
            $xml .= '<id>' . htmlspecialchars($post->url) . '</id>';
            $xml .= '<updated>' . $post->updated_at->toAtomString() . '</updated>';
            $xml .= '<published>' . $post->published_at->toAtomString() . '</published>';
            $xml .= '<author><name>' . htmlspecialchars($post->author->name ?? 'News24.az') . '</name></author>';
            $xml .= '<summary type="html"><![CDATA[' . htmlspecialchars($post->meta_description ?? strip_tags($post->excerpt ?? '')) . ']]></summary>';
            $xml .= '<content type="html"><![CDATA[' . $post->content . ']]></content>';
            
            if ($post->main_category) {
                $xml .= '<category term="' . htmlspecialchars($post->main_category->name) . '" />';
            }
            
            if ($post->featured_image) {
                $xml .= '<media:thumbnail url="' . htmlspecialchars($post->featured_image) . '" />';
            }
            
            $xml .= '</entry>';
        }

        $xml .= '</feed>';

        return response($xml, 200)
            ->header('Content-Type', 'application/atom+xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=300');
    }
}

