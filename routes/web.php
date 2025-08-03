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

// Маршруты для удаления доменов и сертификатов
Route::delete('/domains/{domain}', [DomainController::class, 'destroy'])->name('domains.destroy');
Route::delete('/certificates/{certificate}', [SslCertificateController::class, 'destroy'])->name('certificates.destroy');
