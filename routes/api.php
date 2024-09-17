<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UserFavoriteController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::group(['middleware' => [EnsureFrontendRequestsAreStateful::class, 'auth.sanctum.api']], function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::post('save-favorites', [UserFavoriteController::class, 'saveFavorite']);
    Route::get('favorites', [UserFavoriteController::class, 'getUserFavorites']);
});

// Public routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('articles', [ArticleController::class, 'index']);
Route::get('articles/{id}', [ArticleController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('sources', [SourceController::class, 'index']);