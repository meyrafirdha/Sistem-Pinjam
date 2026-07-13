<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Anggota;
use App\Http\Controllers\Auth as AuthController;
use App\Http\Controllers\Pegawai;
use App\Http\Controllers\Profile;
use Illuminate\Support\Facades\Route;

// --- GUEST ROUTES ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- AUTHENTICATED ROUTES ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/ganti-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/ganti-password', [AuthController::class, 'changePassword'])->name('password.change');

    Route::get('/profil', [Profile::class, 'show'])->name('profile.show');
    Route::get('/profil/edit', [Profile::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [Profile::class, 'update'])->name('profile.update');
});

// --- REDIRECT ROOT ---
Route::get('/', function () {
    return redirect('/login');
});

// --- ANGGOTA (DASHBOARD) ROUTES ---
Route::middleware(['auth', 'role:admin,anggota'])
    ->prefix('dashboard')
    ->name('anggota.')
    ->group(function () {
        Route::get('/', [Anggota::class, 'dashboard'])->name('dashboard');
        Route::get('/pinjaman', [Anggota::class, 'index'])->name('pinjaman.index');
        Route::get('/pinjaman/ajukan', [Anggota::class, 'create'])->name('pinjaman.create');
        Route::post('/pinjaman', [Anggota::class, 'store'])->name('pinjaman.store');
        Route::get('/pinjaman/{pinjaman}', [Anggota::class, 'show'])->name('pinjaman.show');
        Route::get('/pinjaman/{pinjaman}/cetak', [Anggota::class, 'cetak'])->name('pinjaman.cetak');
        Route::get('/pinjaman/{pinjaman}/unduh', [Anggota::class, 'unduhPdf'])->name('pinjaman.unduh');
    });

// --- ADMIN ROUTES ---
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [Admin::class, 'dashboard'])->name('dashboard');

        // Admin Pinjaman Management
        Route::get('/pinjaman', [Admin::class, 'index'])->name('pinjaman.index');
        Route::get('/pinjaman/{pinjaman}', [Admin::class, 'show'])->name('pinjaman.show');
        Route::post('/pinjaman/{pinjaman}/acc', [Admin::class, 'acc'])->name('pinjaman.acc');
        Route::post('/pinjaman/{pinjaman}/decline', [Admin::class, 'decline'])->name('pinjaman.decline');
        Route::put('/pinjaman/{pinjaman}/juru-bayar', [Admin::class, 'updateJuruBayar'])->name('pinjaman.juru-bayar');

        // Admin Pegawai Management
        Route::get('/pegawai', [Pegawai::class, 'index'])->name('pegawai.index');
        Route::get('/pegawai/tambah', [Pegawai::class, 'create'])->name('pegawai.create');
        Route::post('/pegawai', [Pegawai::class, 'store'])->name('pegawai.store');
        Route::get('/pegawai/{pegawai}', [Pegawai::class, 'show'])->name('pegawai.show');
        Route::get('/pegawai/{pegawai}/edit', [Pegawai::class, 'edit'])->name('pegawai.edit');
        Route::put('/pegawai/{pegawai}', [Pegawai::class, 'update'])->name('pegawai.update');
        Route::post('/pegawai/{pegawai}/reset-password', [Pegawai::class, 'resetPassword'])->name('pegawai.reset-password');
        Route::delete('/pegawai/{pegawai}', [Pegawai::class, 'destroy'])->name('pegawai.destroy');
    });