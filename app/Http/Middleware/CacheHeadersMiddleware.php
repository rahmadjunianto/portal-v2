<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeadersMiddleware
{
    /**
     * Handle an incoming request.
     * Adds Last-Modified, ETag, and Cache-Control headers to responses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only add caching headers for successful GET requests
        if (!$request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return $response;
        }

        // Get the request's Last-Modified header (if any)
        $requestLastModified = $request->header('If-Modified-Since');

        // Get the resource's last modification time
        $lastModified = $this->getLastModifiedTime($request);

        // Generate ETag
        $etag = $this->generateEtag($response, $lastModified);

        // Add Last-Modified header
        if ($lastModified) {
            $response->headers->set('Last-Modified', $lastModified->toRfc1123String());
        }

        // Add ETag header
        $response->headers->set('ETag', $etag);

        // Check if client has cached version
        if ($requestLastModified && $lastModified) {
            $requestTime = strtotime($requestLastModified);
            $resourceTime = $lastModified->timestamp;

            // If resource hasn't changed, return 304 Not Modified
            if ($resourceTime <= $requestTime) {
                return response('', 304, [
                    'Last-Modified' => $lastModified->toRfc1123String(),
                    'ETag' => $etag,
                ]);
            }
        }

        // Check If-None-Match header (ETag matching)
        $ifNoneMatch = $request->header('If-None-Match');
        if ($ifNoneMatch && $ifNoneMatch === $etag) {
            return response('', 304, [
                'Last-Modified' => $lastModified ? $lastModified->toRfc1123String() : '',
                'ETag' => $etag,
            ]);
        }

        // Add Cache-Control header
        $this->setCacheControl($response, $request);

        return $response;
    }

    /**
     * Determine the last modification time for the request.
     */
    protected function getLastModifiedTime(Request $request): ?\Carbon\Carbon
    {
        // Try to get from route parameter (e.g., for single resource pages)
        $routeName = $request->route()?->getName();

        // Homepage - use latest content update
        if ($routeName === 'home') {
            return $this->getLatestContentUpdate();
        }

        // For individual content pages, you can extend this logic
        // to get the updated_at from the specific model

        // Default to current time for dynamic content
        return now();
    }

    /**
     * Get the latest update time across all content types.
     */
    protected function getLatestContentUpdate(): ?\Carbon\Carbon
    {
        $latestPost = \App\Models\Post::max('updated_at');
        $latestAgenda = \App\Models\Agenda::max('updated_at');
        $latestPage = \App\Models\Page::max('updated_at');
        $latestDownload = \App\Models\Download::max('updated_at');

        $dates = array_filter([
            $latestPost,
            $latestAgenda,
            $latestPage,
            $latestDownload,
        ]);

        if (empty($dates)) {
            return null;
        }

        return \Carbon\Carbon::parse(max($dates));
    }

    /**
     * Generate an ETag for the response.
     */
    protected function generateEtag(Response $response, ?\Carbon\Carbon $lastModified): string
    {
        $content = $response->getContent();
        $hash = md5($content . ($lastModified?->timestamp ?? time()));

        return '"' . $hash . '"';
    }

    /**
     * Set appropriate Cache-Control header based on route.
     */
    protected function setCacheControl(Response $response, Request $request): void
    {
        $routeName = $request->route()?->getName();

        $cacheRules = [
            // Static pages - longer cache
            'accessibility' => 'public, max-age=86400, s-maxage=86400, stale-while-revalidate=604800',
            'privacy' => 'public, max-age=86400, s-maxage=86400, stale-while-revalidate=604800',
            'profil' => 'public, max-age=86400, s-maxage=86400, stale-while-revalidate=604800',

            // Content listing pages - moderate cache
            'posts.index' => 'public, max-age=3600, s-maxage=3600, stale-while-revalidate=86400',
            'posts.announcements' => 'public, max-age=3600, s-maxage=3600, stale-while-revalidate=86400',
            'agendas.index' => 'public, max-age=3600, s-maxage=3600, stale-while-revalidate=86400',
            'downloads.index' => 'public, max-age=3600, s-maxage=3600, stale-while-revalidate=86400',

            // Homepage - short cache with revalidation
            'home' => 'public, max-age=1800, s-maxage=1800, stale-while-revalidate=3600',

            // Single content pages - cache based on content age
            'posts.show' => 'public, max-age=3600, s-maxage=3600, stale-while-revalidate=86400',
            'agendas.show' => 'public, max-age=3600, s-maxage=3600, stale-while-revalidate=86400',
            'pages.show' => 'public, max-age=3600, s-maxage=3600, stale-while-revalidate=86400',
            'downloads.download' => 'public, max-age=3600, s-maxage=3600, stale-while-revalidate=86400',

            // Contact form - no cache
            'contact' => 'no-cache, no-store, must-revalidate',
        ];

        $cacheControl = $cacheRules[$routeName] ?? 'public, max-age=1800, s-maxage=1800, stale-while-revalidate=3600';

        $response->headers->set('Cache-Control', $cacheControl);
    }
}
