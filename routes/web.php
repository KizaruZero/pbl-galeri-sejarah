<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Article;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\InstallController;

// Installation routes - must be first and outside any middleware
Route::get('/install', function () {
    return Inertia::render('Views/ConfigurationForm');
});

Route::get('/registration-company', function () {
    return Inertia::render('Views/RegistrationCompany');
})->name('registration-company')
    ->withoutMiddleware('web');

Route::post('/install', [InstallController::class, 'install'])
    ->name('install.post')
    ->middleware('web');

// Additional installation-related routes
Route::get('/migrate-fresh', [InstallController::class, 'migrateFresh']);
Route::post('/change-database', [InstallController::class, 'changeDatabase'])
    ->name('change-database')
    ->withoutMiddleware('web');

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
Route::get('/article/{slug}', fn() => Inertia::render('Views/ArticleDetailView'));
Route::get('/member', fn() => Inertia::render('Views/MemberView'))->middleware('guest');
Route::get('/contact', fn() => Inertia::render('Views/MemberView'))->middleware('auth');
Route::get('/upload-photo', fn() => Inertia::render('Views/FormUploadPhoto'));
Route::get('/upload-video', fn() => Inertia::render('Views/FormUploadVideo'));
Route::get('/events', fn() => Inertia::render('Views/EventView'));
Route::get('/events/{slug}', fn() => Inertia::render('Views/EventDetailView'));
Route::get('/gallery', fn() => Inertia::render('Views/GalleryView'));
Route::get('/gallery/{slug}', function ($slug) {
    return Inertia::render('Views/ListGallery');
});
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/photo/{slug}', [PhotoController::class, 'show'])->name('photo.show');
Route::get('/video/{slug}', [VideoController::class, 'show'])->name('video.show');
Route::get('/gallery-photo/{slug}', function () {
    return Inertia::render('Views/PhotoDetail'); // nama Vue component di `resources/js/Pages/PhotoDetail.vue`
});
Route::get('/gallery-photo/{slug1}/{slug}', function () {
    return Inertia::render('Views/PhotoDetail'); // nama Vue component di `resources/js/Pages/PhotoDetail.vue`
});
Route::get('/gallery-video/{slug}', function () {
    return Inertia::render('Views/VideoDetail'); // nama Vue component di `resources/js/Pages/PhotoDetail.vue`
});
Route::get('/gallery-video/{slug1}/{slug}', function () {
    return Inertia::render('Views/VideoDetail'); // nama Vue component di `resources/js/Pages/PhotoDetail.vue`
});
Route::get('/detail/{slug}', fn($slug) => Inertia::render('Views/DetailSejarah', ['slug' => $slug]))->name('Detail');

    Route::get('/profile-page', fn() => Inertia::render('Views/ProfileView'))
        // ->middleware('auth', 'role:member')
    ;

/**
 * Routing untuk pengguna terautentikasi
 */
Route::middleware('auth')->group(function () {
    // Halaman profile (auth bawaan Laravel Breeze/Jetstream)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

    // user post
    Route::post('/api/content-photo', [PhotoController::class, 'store']);
    Route::post('/api/content-video', [VideoController::class, 'store']);

});

require __DIR__ . '/auth.php';
require __DIR__ . '/api.php';
