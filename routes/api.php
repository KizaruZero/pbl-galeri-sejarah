<?php

use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontendController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// routes/api.php
Route::get('/search', [SearchController::class, 'search']);
Route::get('/content-photo', [PhotoController::class, 'index']);
Route::get('/content-photo/{slug}', [PhotoController::class, 'show']);
Route::post('/content-photo', [PhotoController::class, 'store']);
Route::get('/content-photo/user/{userId}', [PhotoController::class, 'getPhotoByUser']);



Route::get('/content-video', [VideoController::class, 'index']);
Route::get('/content-video/{slug}', [VideoController::class, 'show']);
Route::get('/article', [ArticleController::class, 'index']);
Route::get('/article/{slug}', [ArticleController::class, 'show']);

// Route baru untuk user
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/content-photo/user/{userId}', [PhotoController::class, 'getPhotoByUser']);
Route::get('/favorite/user/{userId}', [PhotoController::class, 'getFavoriteByUser']);
Route::get('/content-video/user/{userId}', [VideoController::class, 'getVideoByUser']);
Route::get('/favorite/user/{userId}', [VideoController::class, 'getFavoriteByUser']);

// Content By Category
Route::get('/category-photo/{slug}', [PhotoController::class, 'getPhotoByCategory']);
Route::get('/category-video/{slug}', [VideoController::class, 'getVideoByCategory']);

// Company Profile
Route::get('/company-profile', [CompanyProfileController::class, 'index']);