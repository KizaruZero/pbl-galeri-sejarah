<?php

use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VideoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// routes/api.php
Route::get('/search', [SearchController::class, 'search']);
Route::get('/content-photo', [PhotoController::class, 'index']);
Route::get('/content-photo/{id}', [PhotoController::class, 'show']);
Route::get('/content-video', [VideoController::class, 'index']);
Route::get('/content-video/{id}', [VideoController::class, 'show']);
