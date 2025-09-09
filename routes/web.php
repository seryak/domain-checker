<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SslReportController;
use App\Http\Controllers\LibadwaitaDemoController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\SslCertificateController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WelcomeController;

// Welcome screen and analytics consent
Route::get('/welcome', [WelcomeController::class, 'welcome'])->name('welcome');
Route::post('/welcome', [WelcomeController::class, 'acceptTerms'])->name('welcome.accept');
Route::get('/analytics-consent', [WelcomeController::class, 'analyticsConsent'])->name('analytics.consent');
Route::post('/analytics-consent', [WelcomeController::class, 'submitAnalyticsConsent'])->name('analytics.consent.submit');

// Redirect to welcome if first launch, otherwise to SSL report
Route::get('/', function () {
    $firstLaunchCompleted = \Native\Laravel\Facades\Settings::get('first_launch_completed', false);
    return $firstLaunchCompleted
        ? redirect()->route('ssl.report')
        : redirect()->route('welcome');
})->name('home');

Route::get('/ssl-report', [SslReportController::class, 'index'])->name('ssl.report');

//Route::get('/ssl-report', [SslReportController::class, 'index'])->name('ssl.report');
Route::post('/trigger-check', [SslReportController::class, 'triggerCheck'])->name('ssl.trigger-check');
Route::post('/check-single', [SslReportController::class, 'checkSingle'])->name('ssl.check-single');

// Маршруты для управления доменами
Route::get('/domains/create', [DomainController::class, 'create'])->name('domains.create');
Route::post('/domains', [DomainController::class, 'store'])->name('domains.store');

// Маршруты для удаления доменов и сертификатов
Route::delete('/domains/{domain}', [DomainController::class, 'destroy'])->name('domains.destroy');
Route::delete('/certificates/{certificate}', [SslCertificateController::class, 'destroy'])->name('certificates.destroy');

// Маршруты для настроек
Route::get('/settings', [SettingsController::class, 'show'])->name('settings.index');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
