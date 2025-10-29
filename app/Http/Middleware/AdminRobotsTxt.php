<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRobotsTxt
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If this is admin domain and requesting robots.txt
        if ($request->getHost() === 'edm.news24.az' && $request->path() === 'robots.txt') {
            return response("User-agent: *\nDisallow: /\n", 200)
                ->header('Content-Type', 'text/plain');
        }

        return $next($request);
    }
}
