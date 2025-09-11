<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        // Запуск проверки обновлений приложения каждый час
        $schedule->command('app-updates:check')
                 ->hourly()
                 ->withoutOverlapping()
                 ->runInBackground();
    })
    ->withMiddleware(function (Middleware $middleware) {
        // Применяем middleware для установки языка ко всем веб-запросам
        $middleware->web(\App\Http\Middleware\SetLanguage::class);

        // Middleware для проверки согласия с условиями использования
        $middleware->web(\App\Http\Middleware\CheckTermsAgreed::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
