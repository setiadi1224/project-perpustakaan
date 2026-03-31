<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard/user', [UserDashboardController::class, 'home'])->name('user.home');
    Route::get('/dashboard/user/kategori', fn() => view('user.kategori'))->name('user.kategori');
    Route::get('/dashboard/user/library', [UserDashboardController::class, 'library'])->name('user.library');
    Route::get('/dashboard/user/riwayat', [UserDashboardController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/dashboard/user/denda',   [UserDashboardController::class, 'denda'])->name('user.denda');
    Route::get('/profile', [App\Http\Controllers\UserDashboardController::class, 'profile'])->name('user.profile');
    Route::post('/profile/update', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
});
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Dashboard Routes (protected by auth + role)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Petugas
    Route::middleware('role:petugas')->group(function () {
        Route::get('/dashboard/petugas', [DashboardController::class, 'petugas'])
            ->name('petugas.dashboard');
    });

    // Kepala Perpustakaan
    Route::middleware('role:kepala_perpustakaan')->group(function () {
        Route::get('/dashboard/kepala', [DashboardController::class, 'kepala'])
            ->name('kepala.dashboard');
    });
});
