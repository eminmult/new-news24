<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller
{
    /**
     * Show author profile page
     */
    public function show($slug)
    {
        // Cache author for 1 hour
        $author = Cache::remember("author_{$slug}", 3600, function() use ($slug) {
            return User::where('slug', $slug)
                ->where('is_active', true)
                ->whereIn('role', [User::ROLE_AUTHOR, User::ROLE_EDITOR, User::ROLE_ADMIN])
                ->firstOrFail();
        });

        // Get author's published posts
        $page = request()->get('page', 1);
        $posts = Cache::remember("author_{$author->id}_posts_page_{$page}", 1800, function() use ($author) {
            return Post::published()
                ->where('author_id', $author->id)
                ->with(['categories', 'tags'])
                ->latest('published_at')
                ->paginate(12);
        });

        // Get author stats
        $stats = Cache::remember("author_{$author->id}_stats", 3600, function() use ($author) {
            return [
                'total_posts' => Post::published()->where('author_id', $author->id)->count(),
                'total_views' => Post::published()->where('author_id', $author->id)->sum('views'),
                'recent_posts' => Post::published()
                    ->where('author_id', $author->id)
                    ->where('published_at', '>=', now()->subDays(30))
                    ->count(),
            ];
        });

        // Get categories
        $categories = Cache::remember('all_categories', 3600, function() {
            return \App\Models\Category::where('is_active', true)
                ->orderBy('order')
                ->get();
        });

        return view('author.show', compact('author', 'posts', 'stats', 'categories'));
    }
}

