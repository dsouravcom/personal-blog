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
        //
    })->create();
