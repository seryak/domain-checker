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
    ->withMiddleware(function (Middleware $middleware) {
        // Применяем middleware для установки языка ко всем веб-запросам
        $middleware->web(\App\Http\Middleware\SetLanguage::class);

        // Middleware для проверки согласия с условиями использования
        $middleware->web(\App\Http\Middleware\CheckTermsAgreed::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
