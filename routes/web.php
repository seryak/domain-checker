<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SslReportController;
use App\Http\Controllers\LibadwaitaDemoController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\SslCertificateController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ssl-report', [SslReportController::class, 'index'])->name('ssl.report');
Route::post('/trigger-check', [SslReportController::class, 'triggerCheck'])->name('ssl.trigger-check');
Route::post('/check-single', [SslReportController::class, 'checkSingle'])->name('ssl.check-single');

// Маршруты для управления доменами
Route::get('/domains/create', [DomainController::class, 'create'])->name('domains.create');
Route::post('/domains', [DomainController::class, 'store'])->name('domains.store');

// Маршруты для удаления доменов и сертификатов
Route::delete('/domains/{domain}', [DomainController::class, 'destroy'])->name('domains.destroy');
Route::delete('/certificates/{certificate}', [SslCertificateController::class, 'destroy'])->name('certificates.destroy');
