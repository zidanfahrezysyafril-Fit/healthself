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
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

    Route::get('/profile', [AuthController::class, 'profile']);
    
    // Dashboard Endpoints
   // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index']);

    // Article Endpoints
    Route::get('/articles', [\App\Http\Controllers\Api\ArticleController::class, 'index']);
    Route::get('/articles/popular', [\App\Http\Controllers\Api\ArticleController::class, 'popular']);
    Route::get('/articles/recommended', [\App\Http\Controllers\Api\ArticleController::class, 'recommended']);
    Route::get('/articles/category/{slug}', [\App\Http\Controllers\Api\ArticleController::class, 'getByCategory']);
    Route::get('/articles/{slug}', [\App\Http\Controllers\Api\ArticleController::class, 'show']);
    Route::post('/articles/{id}/bookmark', [\App\Http\Controllers\Api\ArticleController::class, 'toggleBookmark']);

    // Chat Endpoints
    Route::get('/chat/history', [ChatController::class, 'history']);
    Route::post('/chat/send', [ChatController::class, 'send']);

    // Mood Endpoints
   // Mood (Sprint A2)
Route::get('/moods/statistics', [\App\Http\Controllers\Api\MoodController::class, 'statistics']);
Route::apiResource('moods', \App\Http\Controllers\Api\MoodController::class);
    
    // Profile Endpoints
    Route::get('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'index']);
    Route::put('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/profile/avatar', [\App\Http\Controllers\Api\ProfileController::class, 'updateAvatar']);
    Route::delete('/profile/avatar', [\App\Http\Controllers\Api\ProfileController::class, 'deleteAvatar']);
    Route::post('/change-password', [\App\Http\Controllers\Api\ProfileController::class, 'changePassword']);
