<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Article;

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


Route::get('/article', fn() => Inertia::render('Views/ArticleView'));
Route::get('/member', fn() => Inertia::render('Views/MemberView'));
Route::get('/events', fn() => Inertia::render('Views/EventView'));
Route::get('/events/{slug}', fn() => Inertia::render('Views/EventDetailView'));
Route::get('/gallery', fn() => Inertia::render('Views/GalleryView'));
Route::get('/gallery/{slug}', function ($slug) {
    return Inertia::render('Views/ListGallery');
});
Route::get('/gallery/{slug1}/{slug}', function () {
    return Inertia::render('Views/PhotoDetail'); // nama Vue component di `resources/js/Pages/PhotoDetail.vue`
});
Route::get('/detail/{slug}', fn($slug) => Inertia::render('Views/DetailSejarah', ['slug' => $slug]))->name('Detail');

Route::get('/profile-page', fn() => Inertia::render('Views/ProfileView'))->middleware('auth', 'role:member');

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
