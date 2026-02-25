<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Global rate limit: 120 requests per minute by default for all API routes (if any)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        // ─── Public Reading (Soft Limit) ──────────────────────────────
        // Allow generous browsing: 120 requests per minute (~2/second)
        // Authenticated users get double the limit.
        RateLimiter::for('read.content', function (Request $request) {
            return Limit::perMinute($request->user() ? 240 : 120)->by($request->ip());
        });

        // ─── Comments & Interactions (Strict) ─────────────────────────
        // Prevent spam: 5 comments per hour.
        RateLimiter::for('write.comment', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        // Prevent like spam: 50 likes per hour.
        RateLimiter::for('write.like', function (Request $request) {
            return Limit::perHour(50)->by($request->ip());
        });

        // ─── Subscription (Strict) ────────────────────────────────────
        // Prevent list bombing: 5 subscriptions per hour.
        RateLimiter::for('write.subscribe', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        // ─── Authentication (Very Strict) ─────────────────────────────
        // Protect login/OTP: 5 attempts per 12 hours.
        RateLimiter::for('auth.strict', function (Request $request) {
            // Key by email + IP to prevent distributed attacks on one account,
            // or single IP attacking multiple accounts.
            return Limit::perMinutes(720, 5)->by($request->input('email') . '|' . $request->ip());
        });

        // ─── Admin Actions (Moderate) ─────────────────────────────────
        // Admins need to work, but we still prevent abuse: 60 actions/min.
        RateLimiter::for('admin.action', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Image uploads are heavier: 20 per minute.
        RateLimiter::for('admin.upload', function (Request $request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
        });
    }
}
