<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MoodController;
use App\Http\Controllers\Api\ProfileController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Protected Auth Endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });
});

// Public Article routes (no auth needed for reading)
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/popular', [ArticleController::class, 'popular']);
Route::get('/articles/recommended', [ArticleController::class, 'recommended']);
Route::get('/articles/category/{slug}', [ArticleController::class, 'getByCategory']);
Route::get('/articles/{slug}', [ArticleController::class, 'show']);

// Protected routes - require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Article bookmark (needs auth)
    Route::post('/articles/{id}/bookmark', [ArticleController::class, 'toggleBookmark']);

    // Chat Endpoints
    Route::get('/chat/history', [ChatController::class, 'history']);
    Route::post('/chat/send', [ChatController::class, 'send']);

    // Mood Endpoints
    Route::get('/moods/statistics', [MoodController::class, 'statistics']);
    Route::apiResource('moods', MoodController::class);

    // Profile Endpoints
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar']);
    Route::post('/change-password', [ProfileController::class, 'changePassword']);
});

