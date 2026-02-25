<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ViewTrackerService
{
    /**
     * Production-grade unique view tracking.
     * Strategy: SHA-256 hash of (IP + User-Agent + post_id + calendar date).
     * One unique view per visitor per post per day.
     * Bot detection via known bot user-agent patterns.
     */
    public function track(Request $request, Post $post): void
    {
        // 1. Skip bots
        if ($this->isBot($request->userAgent() ?? '')) {
            return;
        }

        $ip        = $request->ip();
        $userAgent = $request->userAgent() ?? 'unknown';
        $today     = now()->format('Y-m-d');

        // 2. Build unique fingerprint for this visitor+post+day
        $viewHash = hash('sha256', $ip . $userAgent . $post->id . $today);

        // 3. Use cache as first-layer dedup (fast, avoids DB hits on repeat requests)
        $cacheKey = "view_tracked:{$viewHash}";
        if (Cache::has($cacheKey)) {
            return;
        }

        // 4. Parse device info from user agent
        $parsed = $this->parseUserAgent($userAgent);

        // 5. Parse UTM parameters and referrer
        $referrerUrl    = $request->header('referer');
        $referrerDomain = $referrerUrl ? parse_url($referrerUrl, PHP_URL_HOST) : null;

        // 6. Write to DB (unique constraint prevents double writes)
        try {
            PostView::create([
                'post_id'          => $post->id,
                'view_hash'        => $viewHash,
                'ip_address'       => $ip,
                'device_type'      => $parsed['device'],
                'browser'          => $parsed['browser'],
                'os'               => $parsed['os'],
                'country_code'     => null, // Extend with a GeoIP library if needed
                'referrer_domain'  => $referrerDomain,
                'referrer_url'     => $referrerUrl,
                'utm_source'       => $request->query('utm_source'),
                'utm_medium'       => $request->query('utm_medium'),
                'utm_campaign'     => $request->query('utm_campaign'),
                'viewed_at'        => now(),
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // Already tracked — no issue
        }

        // 7. Cache for 24h so we don't hammer DB on refresh
        Cache::put($cacheKey, true, now()->addHours(24));
    }

    /**
     * Known bot user-agent keywords (lightweight check).
     */
    private function isBot(string $userAgent): bool
    {
        $botPatterns = [
            'bot', 'crawler', 'spider', 'slurp', 'mediapartners',
            'googlebot', 'bingbot', 'yandex', 'duckduck', 'baidu',
            'sogou', 'exabot', 'facebot', 'ia_archiver', 'semrush',
            'ahrefs', 'mj12bot', 'dotbot', 'rogerbot',
            'curl/', 'wget/', 'python-requests', 'go-http-client',
            'java/', 'libwww', 'httrack', 'okhttp',
        ];

        $ua = strtolower($userAgent);
        foreach ($botPatterns as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Simple user-agent parser (no external library).
     * Returns device_type, browser, os.
     */
    private function parseUserAgent(string $ua): array
    {
        $ua = strtolower($ua);

        // Device type
        $device = 'desktop';
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            $device = 'mobile';
        } elseif (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            $device = 'tablet';
        }

        // Browser
        $browser = 'other';
        foreach (['edg' => 'Edge', 'opr' => 'Opera', 'chrome' => 'Chrome', 'firefox' => 'Firefox', 'safari' => 'Safari', 'msie' => 'IE', 'trident' => 'IE'] as $key => $name) {
            if (str_contains($ua, $key)) {
                $browser = $name;
                break;
            }
        }

        // OS
        $os = 'other';
        foreach (['windows' => 'Windows', 'macintosh' => 'macOS', 'linux' => 'Linux', 'android' => 'Android', 'iphone' => 'iOS', 'ipad' => 'iOS'] as $key => $name) {
            if (str_contains($ua, $key)) {
                $os = $name;
                break;
            }
        }

        return compact('device', 'browser', 'os');
    }
}
