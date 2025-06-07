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
use App\Http\Controllers\NotificationController;
// Installation routes - must be first and outside any middleware
Route::get('/install', function () {
    return Inertia::render('Views/RegistrationForm');
});

Route::get('/requirements', function () {
    return Inertia::render('Views/RequirementsCheck');
})->name('requirements')
    ->withoutMiddleware('web');
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
})->name('/')->middleware(['routestatistics']);

/**
 * Routing Vue Pages (non-auth)
 */
// middleware web
Route::middleware('web')->group(function () {
    Route::get('/article', fn() => Inertia::render('Views/ArticleView'))->middleware(['routestatistics']);
    ;
    Route::get('/article/{slug}', fn() => Inertia::render('Views/ArticleDetailView'));
    Route::get('/member', fn() => Inertia::render('Views/MemberView'))->middleware(['guest', 'routestatistics']);
    Route::get('/contact', fn() => Inertia::render('Views/MemberView'))->middleware(['auth', 'routestatistics']);
    Route::get('/upload-photo', fn() => Inertia::render('Views/FormUploadPhoto'));
    Route::get('/upload-video', fn() => Inertia::render('Views/FormUploadVideo'));
    Route::get('/events', fn() => Inertia::render('Views/EventView'))->middleware(['routestatistics']);
    Route::get('/events/{slug}', fn() => Inertia::render('Views/EventDetailView'));
    Route::get('/gallery', fn() => Inertia::render('Views/GalleryView'))->middleware(['routestatistics']);
    Route::get('/gallery/{slug}', function ($slug) {
        return Inertia::render('Views/ListGallery');
    });
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/api/search', [SearchController::class, 'search']);
    Route::get('/api/search/data', [SearchController::class, 'getSearchData']);
    Route::get('/photo/{slug}', [PhotoController::class, 'show'])->name('photo.show');
    Route::post('/photos/import', [PhotoController::class, 'import'])->middleware('auth');
    Route::get('/video/{slug}', [VideoController::class, 'show'])->name('video.show');
    Route::post('/videos/import', [VideoController::class, 'import'])->middleware('auth');
    Route::get('/gallery-photo/{slug}', function () {
        return Inertia::render('Views/PhotoDetail');
    });
    Route::get('/gallery-photo/{slug1}/{slug}', function () {
        return Inertia::render('Views/PhotoDetail');
    });
    Route::get('/gallery-video/{slug}', function () {
        return Inertia::render('Views/VideoDetail');
    });
    Route::get('/gallery-video/{slug1}/{slug}', function () {
        return Inertia::render('Views/VideoDetail');
    });
    Route::get('/detail/{slug}', fn($slug) => Inertia::render('Views/DetailSejarah', ['slug' => $slug]))->name('Detail');

    Route::get('/profile-page', fn() => Inertia::render('Views/ProfileView'))
        ->middleware('auth')->name('profile-page');

    /**
     * Routing untuk pengguna terautentikasi
     */
    Route::middleware(['auth', 'verified'])->group(function () {
        // Halaman profile (auth bawaan Laravel Breeze/Jetstream)
        Route::get('/profile-page', fn() => Inertia::render('Views/ProfileView'))->name('profile-page');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // user post
    Route::post('/api/content-photo', [PhotoController::class, 'store']);
    Route::post('/api/content-video', [VideoController::class, 'store']);
    Route::get('/api/content-photo/{id}/edit', [PhotoController::class, 'edit']); // Add this line
    Route::put('/api/content-photo/{id}', [PhotoController::class, 'updatePhotoByUser']); // Add this line
    Route::get('/api/content-video/edit/{id}', [VideoController::class, 'edit']);
    Route::post('/api/content-video/{id}', [VideoController::class, 'updateVideoByUser']);
    // get popular content
    Route::get('/api/popular-photo', [PhotoController::class, 'getPopularPhotos']);

    Route::get('/api/popular-video', [VideoController::class, 'getPopularVideos']);

    Route::get('/api/notifications', [NotificationController::class, 'index']);
    Route::post('/api/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::post('/api/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);

});

Route::get('/update-photo/{id}', fn() => Inertia::render('Views/FormUpdateUploadPhoto'));
Route::get('/update-video/{id}', fn() => Inertia::render('Views/FormUpdateUploadVideo'));

Route::get('/bulk', fn() => Inertia::render('Views/BulkUploadView'));
Route::post('/api/bulk-upload', [PhotoController::class, 'bulkUpload']);

// Only load auth routes if they haven't been loaded yet
if (!Route::has('register')) {
    require __DIR__ . '/auth.php';
}

require __DIR__ . '/api.php';
