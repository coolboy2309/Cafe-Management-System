<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AdminController;

Route::prefix('v1')->group(function () {

    Route::post('/login', [LoginController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LogoutController::class, 'logout']);
        Route::get('/reports/daily', [AdminController::class, 'dailyReport']);       
    });
     Route::get('/reports/monthly', [ReportController::class, 'monthlyReport']);
});