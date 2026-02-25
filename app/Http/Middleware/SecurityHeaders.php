<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and set security response headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent the page from being loaded in an iframe (Clickjacking)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME-type sniffing (MIME confusion attacks)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable built-in XSS filter in older browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Only send origin in referrer when navigating within same origin
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Disable FLOC / restrict powerful features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');

        // Strict Transport Security (HTTPS only - enable in production)
        // Forces browsers to only access site via HTTPS for 1 year
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Content Security Policy
        // Permissive policy to ensure all styles, scripts, and assets load correctly
        $csp = implode('; ', [
            "default-src * data: blob: 'unsafe-inline' 'unsafe-eval'",
            "script-src * data: blob: 'unsafe-inline' 'unsafe-eval'",
            "style-src * data: blob: 'unsafe-inline'",
            "img-src * data: blob:",
            "font-src * data: blob:",
            "connect-src * data: blob: ws: wss:",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
