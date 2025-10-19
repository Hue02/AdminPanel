<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\TriviaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Redirect root URL to the admin login page
Route::get('/', function () {
    return redirect()->route('auth.login');
});

// Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Requires Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Reports
    Route::get('/reports', [UserProgressController::class, 'index'])->name('reports.index');

    // User Management
    Route::resource('users', UserController::class); // Remove `except(['create', 'store', 'show'])`

    // Admin Management (Teachers)
    Route::resource('admins', AdminController::class)->except(['create', 'store', 'show']);

    // Category Management
    Route::resource('categories', CategoryController::class);

    // Profile Management
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');

    Route::post('/profile/picture', [AdminProfileController::class, 'updateProfilePicture'])
        ->name('profile.picture.update');

    Route::patch('/profile/update', [AdminProfileController::class, 'updateProfile'])
        ->name('profile.update');

    Route::patch('/password/update', [AdminProfileController::class, 'updatePassword'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Trivia Management Routes
|--------------------------------------------------------------------------
*/

// Admin Trivia Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.trivia.')->group(function () {
    Route::get('/trivia', [TriviaController::class, 'index'])->name('index');
    Route::get('/trivia/create', [TriviaController::class, 'create'])->name('create');
    Route::post('/trivia', [TriviaController::class, 'store'])->name('store');
    Route::get('/trivia/{id}/edit', [TriviaController::class, 'edit'])->name('edit');
    Route::put('/trivia/{id}', [TriviaController::class, 'update'])->name('update');
    Route::delete('/trivia/{id}', [TriviaController::class, 'destroy'])->name('destroy');
    Route::post('/trivia/upload', [TriviaController::class, 'uploadCSV'])->name('upload');
});

// Teacher Trivia Routes
Route::middleware(['auth:admin'])->prefix('teacher')->name('teacher.trivia.')->group(function () {
    Route::get('/trivia', [TriviaController::class, 'index'])->name('index');
    Route::get('/trivia/create', [TriviaController::class, 'create'])->name('create');
    Route::post('/trivia', [TriviaController::class, 'store'])->name('store');
    Route::get('/trivia/{id}/edit', [TriviaController::class, 'edit'])->name('edit');
    Route::put('/trivia/{id}', [TriviaController::class, 'update'])->name('update');
    Route::delete('/trivia/{id}', [TriviaController::class, 'destroy'])->name('destroy');
    Route::post('/trivia/upload', [TriviaController::class, 'uploadCSV'])->name('upload');
});

// Teacher User Management Routes
Route::middleware(['auth:admin'])->prefix('teacher')->name('teacher.users.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('destroy');
});

// Teacher Profile Routes (Reusing AdminProfileController)
Route::middleware(['auth:admin'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/profile', function () {
        return view('teacher.profile');
    })->name('profile');

    Route::post('/profile/picture', [AdminProfileController::class, 'updateProfilePicture'])
        ->name('profile.picture.update');

    Route::patch('/profile/update', [AdminProfileController::class, 'updateProfile'])
        ->name('profile.update');

    Route::patch('/password/update', [AdminProfileController::class, 'updatePassword'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Teacher Routes (Requires Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\UserProgressController::class, 'teacherDashboard'])
        ->name('dashboard');
});