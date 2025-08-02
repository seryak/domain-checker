<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SslReportController;
use App\Http\Controllers\LibadwaitaDemoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ssl-report', [SslReportController::class, 'index'])->name('ssl.report');
