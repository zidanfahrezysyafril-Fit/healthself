<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ArticleController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

    Route::get('/profile', [AuthController::class, 'profile']);
    
    // Dashboard Endpoints
    Route::get('/dashboard/articles', [DashboardController::class, 'articles']);
    Route::get('/dashboard/quotes', [DashboardController::class, 'quotes']);
    Route::get('/dashboard/moods/today', [DashboardController::class, 'moodsToday']);

    // Article Endpoints
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show']);
