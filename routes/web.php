<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetugasDashboardController;
use App\Http\Controllers\UserDashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])
    ->prefix('dashboard/user')
    ->name('user.')
    ->group(function () {

        Route::get('/', [UserDashboardController::class, 'home'])->name('home');
        Route::get('/kategori', [UserDashboardController::class, 'kategori'])->name('kategori');
        Route::get('/library', [UserDashboardController::class, 'library'])->name('library');
        Route::get('/library/{id}', [UserDashboardController::class, 'detailBuku'])
         ->name('buku.detail');
        Route::get('/riwayat', [UserDashboardController::class, 'riwayat'])->name('riwayat');
        Route::get('/denda', [UserDashboardController::class, 'denda'])->name('denda');
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])
    ->prefix('dashboard/petugas')
    ->name('petugas.')
    ->group(function () {

        // Dashboard
        Route::get('/', [PetugasDashboardController::class, 'home'])->name('home');

        // Anggota
        Route::get('/anggota', [PetugasDashboardController::class, 'anggota'])->name('anggota');
        Route::post('/anggota/store', [PetugasDashboardController::class, 'store'])->name('anggota.store');
        Route::put('/anggota/update/{id}', [PetugasDashboardController::class, 'update'])->name('anggota.update');
        Route::delete('/anggota/delete/{id}', [PetugasDashboardController::class, 'destroy'])->name('anggota.delete');

        // 🔥 BUKU (INI YANG DITAMBAHKAN)
        Route::get('/buku', [PetugasDashboardController::class, 'buku'])->name('buku');
        Route::post('/buku/store', [PetugasDashboardController::class, 'storeBuku'])->name('buku.store');
        Route::put('/buku/update/{id}', [PetugasDashboardController::class, 'updateBuku'])->name('buku.update');
        Route::delete('/buku/delete/{id}', [PetugasDashboardController::class, 'deleteBuku'])->name('buku.delete');
});

/*
|--------------------------------------------------------------------------
| KEPALA PERPUSTAKAAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kepala_perpustakaan'])
    ->prefix('dashboard/kepala')
    ->name('kepala.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'kepala'])->name('home');
});