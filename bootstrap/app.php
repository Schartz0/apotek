<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Global Middleware
        |--------------------------------------------------------------------------
        | Middleware ini akan dijalankan di setiap request.
        | Termasuk session, CSRF, validasi input, dll.
        */
        $middleware->use([
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Route Middleware (alias)
        |--------------------------------------------------------------------------
        | Kamu bisa pakai seperti `->middleware('auth')` di route web.php
        */
        $middleware->alias([
            'auth' => Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
