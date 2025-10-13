<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectOldDleUrls
{
    /**
     * Handle an incoming request.
     *
     * Перехватывает старые URL из DLE формата:
     * /category/12345-slug-name.html
     *
     * И делает 301 редирект на новый URL:
     * /category/slug-name
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        // Проверяем соответствие паттерну: category/ID-slug.html
        // Примеры:
        // olay/35448-efendinin-juri-oldugu-musabiqede-hali-pisleshdi-video.html
        // olay/12345-some-post-title.html
        if (preg_match('#^([^/]+)/(\d+)-([^/]+)\.html$#', $path, $matches)) {
            $categorySlug = $matches[1];  // olay
            $postId = $matches[2];        // 35448 (не используем, так как в old_url только slug)
            $slug = $matches[3];          // efendinin-juri-oldugu-musabiqede-hali-pisleshdi-video

            // Ищем пост по old_url (который содержит только slug без ID)
            $post = Post::where('old_url', $slug)->first();

            if ($post) {
                // Получаем новый URL поста через аксессор
                $newUrl = $post->url;

                // 301 редирект на новый URL
                return redirect($newUrl, 301);
            }
        }

        // Также проверяем вариант без .html (если вдруг есть)
        if (preg_match('#^([^/]+)/(\d+)-([^/]+)$#', $path, $matches)) {
            $categorySlug = $matches[1];
            $postId = $matches[2];
            $slug = $matches[3];

            $post = Post::where('old_url', $slug)->first();

            if ($post) {
                $newUrl = $post->url;
                return redirect($newUrl, 301);
            }
        }

        return $next($request);
    }
}
