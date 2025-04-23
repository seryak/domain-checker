<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ssl-report', [\App\Http\Controllers\SslReportController::class, 'index'])
    ->name('ssl.report');
