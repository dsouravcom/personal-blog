<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        // Append security headers to every web response
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        // Block malicious request patterns
        $middleware->append(\App\Http\Middleware\BlockMaliciousRequests::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Whoa there! Please slow down. You are making too many requests.',
                    'retry_after' => $e->getHeaders()['Retry-After'] ?? 60,
                ], 429);
            }
            
            // For non-AJAX requests, we still need to return a response.
            // We can return a simple view or redirect back with an error message.
            return back()->with('error', 'Too many requests. Please slow down.');
        });
    })->create();
