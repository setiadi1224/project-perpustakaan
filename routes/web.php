<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\KepalaDashboardController;
use App\Http\Controllers\PetugasDashboardController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
// logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
// user/anggota
Route::middleware(['auth', 'role:user'])
    ->prefix('dashboard/user')
    ->name('user.')
    ->group(function () {
        Route::get('/', [UserDashboardController::class, 'home'])->name('home');
        Route::get('/kategori', [UserDashboardController::class, 'kategori'])->name('kategori');
        Route::get('/library', [UserDashboardController::class, 'library'])->name('library');
        Route::get('/library/{id}', [UserDashboardController::class, 'detailBuku'])->name('buku.detail');
        Route::get('/riwayat', [UserDashboardController::class, 'riwayat'])->name('riwayat');
        Route::get('/denda', [UserDashboardController::class, 'denda'])->name('denda');
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/pinjam/{id}', [UserDashboardController::class, 'pinjam'])->name('pinjam');
        Route::post('/return/{id}', [UserDashboardController::class, 'returnBuku'])->name('return');
        Route::post('/bayar/{id}', [UserDashboardController::class, 'bayar'])->name('bayar');
    });
// petugas
Route::middleware(['auth', 'role:petugas'])
    ->prefix('dashboard/petugas')
    ->name('petugas.')
    ->group(function () {
        Route::get('/', [PetugasDashboardController::class, 'home'])->name('home');
        Route::get('/anggota', [PetugasDashboardController::class, 'anggota'])->name('anggota');
        Route::post('/anggota/store', [PetugasDashboardController::class, 'store'])->name('anggota.store');
        Route::put('/anggota/update/{id}', [PetugasDashboardController::class, 'update'])->name('anggota.update');
        Route::delete('/anggota/delete/{id}', [PetugasDashboardController::class, 'destroy'])->name('anggota.delete');
        Route::get('/buku', [PetugasDashboardController::class, 'buku'])->name('buku');
        Route::post('/buku/store', [PetugasDashboardController::class, 'storeBuku'])->name('buku.store');
        Route::put('/buku/update/{id}', [PetugasDashboardController::class, 'updateBuku'])->name('buku.update');
        Route::delete('/buku/delete/{id}', [PetugasDashboardController::class, 'deleteBuku'])->name('buku.delete');
        Route::get('/kategori', [PetugasDashboardController::class, 'kategori'])->name('kategori');
        Route::post('/kategori/store', [PetugasDashboardController::class, 'storeKategori'])->name('kategori.store');
        Route::delete('/kategori/delete/{id}', [PetugasDashboardController::class, 'deleteKategori'])->name('kategori.delete');
        Route::get('/peminjaman', [PetugasDashboardController::class, 'peminjaman'])->name('peminjaman');
        Route::post('/peminjaman/approve/{id}', [PetugasDashboardController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/tolak/{id}', [PetugasDashboardController::class, 'tolakPeminjaman'])->name('peminjaman.tolak');
        Route::post('/peminjaman/return/{id}', [PetugasDashboardController::class, 'returnBuku'])->name('peminjaman.return');
        Route::get('/pengembalian', [PetugasDashboardController::class, 'pengembalian'])->name('pengembalian');
        Route::post('/pengembalian/{id}/approve', [PetugasDashboardController::class, 'approvePengembalian'])
            ->name('pengembalian.approve');
        Route::get('/denda', [PetugasDashboardController::class, 'denda'])->name('denda');
        Route::post('/denda/konfirmasi/{id}', [PetugasDashboardController::class, 'konfirmasi'])->name('konfirmasi');
        Route::post('/denda/tolak/{id}', [PetugasDashboardController::class, 'tolak'])->name('tolak');
    });
// kepala perpustakaan
Route::middleware(['auth', 'role:kepala_perpustakaan'])
    ->prefix('dashboard/kepala')
    ->name('kepala.')
    ->group(function () {
        Route::get('/', [KepalaDashboardController::class, 'home'])->name('home');
        Route::get('/petugas', [KepalaDashboardController::class, 'petugas'])->name('petugas');
        Route::post('/petugas/store', [KepalaDashboardController::class, 'storePetugas'])->name('petugas.store');
        Route::put('/petugas/update/{id}', [KepalaDashboardController::class, 'updatePetugas'])->name('petugas.update');
        Route::delete('/petugas/delete/{id}', [KepalaDashboardController::class, 'deletePetugas'])->name('petugas.delete');
        Route::get('/laporan/peminjaman', [KepalaDashboardController::class, 'laporanPeminjaman'])->name('laporan.peminjaman');
        Route::get('/laporan/denda', [KepalaDashboardController::class, 'laporanDenda'])->name('laporan.denda');
        Route::get('/laporan/anggota', [KepalaDashboardController::class, 'laporanAnggota'])->name('laporan.anggota');
        Route::get('/laporan/peminjaman/cetak', [KepalaDashboardController::class, 'cetaklaporanPeminjaman'])->name('laporan.peminjaman.cetak');
        Route::get('laporan/denda/cetak', [KepalaDashboardController::class, 'cetakLaporanDenda'])
            ->name('laporan.cetak_denda');
        Route::get('/security', [KepalaDashboardController::class, 'security'])
            ->name('security');
    });
