<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * Adds security headers to protect against common vulnerabilities:
     * - HSTS: Forces HTTPS connections
     * - X-Frame-Options: Prevents clickjacking attacks
     * - X-Content-Type-Options: Prevents MIME type sniffing
     * - Referrer-Policy: Controls referrer information
     * - Permissions-Policy: Controls browser features
     * - CSP: Content Security Policy to prevent XSS attacks
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only add security headers for HTML responses
        if ($this->shouldAddHeaders($response)) {
            $this->addSecurityHeaders($response, $request);
        }

        return $response;
    }

    /**
     * Check if headers should be added to this response
     */
    protected function shouldAddHeaders(Response $response): bool
    {
        // Only add headers to HTML responses
        $contentType = $response->headers->get('Content-Type');

        if ($contentType && str_contains($contentType, 'text/html')) {
            return true;
        }

        // Also add to responses without Content-Type set (likely HTML)
        if (!$response->headers->has('Content-Type') && $response->getContent()) {
            return true;
        }

        return false;
    }

    /**
     * Add all security headers to the response
     */
    protected function addSecurityHeaders(Response $response, Request $request): void
    {
        $host = $request->getHost();
        $isHttps = $request->isSecure();

        // 1. Strict-Transport-Security (HSTS) - Force HTTPS
        // max-age in seconds (1 year), includeSubDomains, preload
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains; preload'
        );

        // 2. X-Frame-Options - Prevent clickjacking
        // DENY: Page cannot be displayed in frame
        $response->headers->set('X-Frame-Options', 'DENY');

        // 3. X-Content-Type-Options - Prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 4. Referrer-Policy - Control referrer information
        // strict-origin-when-cross-origin: Send full URL for same-origin, origin for cross-origin
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 5. Permissions-Policy - Control browser features
        // Disable unnecessary features that could be exploited
        $response->headers->set(
            'Permissions-Policy',
            'accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(self), usb=(), interest-cohort=()'
        );

        // 6. Content-Security-Policy - Prevent XSS and data injection
        // Strict CSP that only allows same-origin resources and trusted external domains
        $cspDirectives = [
            "default-src 'self'",
            // Allow inline styles for Tailwind CSS
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.tailwindcss.com",
            // Allow scripts from trusted CDN sources (Alpine.js, Tailwind)
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://unpkg.com",
            // Element-level script sources for script tags
            "script-src-elem 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://unpkg.com",
            // Allow fonts from Google
            "font-src 'self' https://fonts.gstatic.com data:",
            // Allow images from same origin and external trusted sources
            "img-src 'self' data: https: blob:",
            // Restrict frame sources
            "frame-src 'self' https://www.google.com https://www.youtube.com",
            // Restrict form action
            "form-action 'self'",
            // Restrict connections to trusted APIs
            "connect-src 'self' https://www.google.com https://www.recaptcha.com https://www.gstatic.com",
            // Base URI restriction
            "base-uri 'self'",
            // Object/source restriction
            "object-src 'none'",
            // Upgrade insecure requests in production
            "upgrade-insecure-requests",
        ];

        $response->headers->set(
            'Content-Security-Policy',
            implode('; ', $cspDirectives)
        );

        // 7. X-XSS-Protection (Legacy but still useful for older browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 8. Cross-Origin policies
        // Note: COEP set to same-origin-allow-popups to allow cross-origin resources (CDN)
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-site');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'credentialless');
    }
}
