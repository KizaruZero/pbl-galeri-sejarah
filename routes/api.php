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
use App\Http\Controllers\UserFavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContentReactionController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// routes/api.php
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search']);
Route::get('/content-photo', [PhotoController::class, 'index']);
Route::get('/content-photo/{slug}', [PhotoController::class, 'show']);
Route::get('/content-photo/user/{userId}', [PhotoController::class, 'getPhotoByUser']);

Route::get('/content-video', [VideoController::class, 'index']);
Route::get('/content-video/{slug}', [VideoController::class, 'show']);
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{slug}', [ArticleController::class, 'show']);

// Route baru untuk user
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/content-photo/user/{userId}', [PhotoController::class, 'getPhotoByUser']);
Route::get('/content-video/user/{userId}', [VideoController::class, 'getVideoByUser']);

// Content By Category
Route::get('/category-photo/{slug}', [PhotoController::class, 'getPhotoByCategory']);
Route::get('/category-video/{slug}', [VideoController::class, 'getVideoByCategory']);

// Company Profile
Route::get('/company-profile', [CompanyProfileController::class, 'index']);

// User Favorite
Route::get('/favorite/total/user/{userId}', [UserFavoriteController::class, 'getTotalFavorites']);
Route::get('/favorite/photo/user/{userId}', [UserFavoriteController::class, 'getPhotoFavoriteByUser']);
Route::get('/favorite/video/user/{userId}', [UserFavoriteController::class, 'getVideoFavoriteByUser']);
Route::post('/user-favorite/photo', [UserFavoriteController::class, 'CreatePhotoFavorite']);
Route::post('/user-favorite/video', [UserFavoriteController::class, 'CreateVideoFavorite']);
Route::delete('/user-favorite/photo', [UserFavoriteController::class, 'DeletePhotoFavorite']);
Route::delete('/user-favorite/video', [UserFavoriteController::class, 'DeleteVideoFavorite']);

// Comment
Route::post('/comment/photo/{id}', [CommentController::class, 'storePhotoComment']);
Route::get('/comment/photo/{id}', [CommentController::class, 'getCommentByContentPhoto']);
Route::delete('/user-comments/{id}', [CommentController::class, 'destroy'])->middleware('auth:sanctum'); // Jika menggunakan authentication
Route::post('/comment/video/{id}', [CommentController::class, 'storeVideoComment']);
Route::get('/comment/video/{id}', [CommentController::class, 'getCommentByContentVideo']);

// Reaction
Route::post('/reaction/photo/{id}', [ContentReactionController::class, 'storePhotoReaction']);
Route::post('/reaction/video/{id}', [ContentReactionController::class, 'storeVideoReaction']);
