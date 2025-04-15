<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/**
 * Halaman Utama (Welcome)
 */
Route::get('/', function () {
    return Inertia::render('Views/HomeView', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('/');

/**
 * Routing Vue Pages (non-auth)
 */
Route::get('/events', fn () => Inertia::render('EventView'));
Route::get('/article', fn () => Inertia::render('ArticleView'));
Route::get('/gallery', fn () => Inertia::render('GalleryView'));
Route::get('/profile-page', fn () => Inertia::render('ProfileView')); // Ganti nama agar tidak bentrok dengan /profile milik auth
Route::get('/detail/{slug}', fn ($slug) => Inertia::render('DetailSejarah', ['slug' => $slug]));

/**
 * Routing untuk pengguna terautentikasi
 */
Route::middleware('auth')->group(function () {
    // Halaman profile (auth bawaan Laravel Breeze/Jetstream)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';