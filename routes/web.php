<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InovasiController;
use App\Http\Controllers\KurasiController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\RtpuController;
use App\Http\Controllers\MarketplaceController;
use Illuminate\Support\Facades\Route;

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// SSO
Route::get('/sso',  [AuthController::class, 'showSso'])->name('sso.login')->middleware('guest');
Route::post('/sso', [AuthController::class, 'ssoLogin'])->name('sso.post');

// Registrasi Eksternal
Route::get('/register',                   [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register/personal',         [AuthController::class, 'registerPersonal'])->name('register.personal.post');
Route::post('/register/perusahaan',       [AuthController::class, 'registerPerusahaan'])->name('register.perusahaan.post');

// ─── Authenticated ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── PENGAJUAN INOVASI (Dosen & Mahasiswa) ─────────────────────────────────
    Route::middleware('role:dosen,mahasiswa')->prefix('inovasi')->name('inovasi.')->group(function () {
        Route::get('/ajukan',     [InovasiController::class, 'create'])->name('create');
        Route::post('/ajukan',    [InovasiController::class, 'store'])->name('store');
        Route::get('/{id}/edit',  [InovasiController::class, 'edit'])->name('edit');
        Route::put('/{id}',       [InovasiController::class, 'update'])->name('update');
        Route::delete('/{id}',    [InovasiController::class, 'destroy'])->name('destroy');
    });
    // Detail inovasi bisa dilihat semua role internal
    Route::get('/inovasi/{id}', [InovasiController::class, 'show'])
        ->middleware('role:dosen,mahasiswa,p3m,rtpu,pimpinan')
        ->name('inovasi.show');

    // ── KURASI (P3M) ──────────────────────────────────────────────────────────
    Route::middleware('role:p3m')->prefix('kurasi')->name('kurasi.')->group(function () {
        Route::get('/{id}',              [KurasiController::class, 'show'])->name('show');
        Route::post('/{id}/setujui',     [KurasiController::class, 'setujui'])->name('setujui');
        Route::post('/{id}/revisi',      [KurasiController::class, 'revisi'])->name('revisi');
        Route::post('/{id}/tolak',       [KurasiController::class, 'tolak'])->name('tolak');
    });

    // ── PENETAPAN HARGA (Pimpinan) ────────────────────────────────────────────
    Route::middleware('role:pimpinan')->prefix('harga')->name('harga.')->group(function () {
        Route::get('/{id}/tetapkan',     [HargaController::class, 'form'])->name('form');
        Route::post('/{id}/tetapkan',    [HargaController::class, 'tetapkan'])->name('tetapkan');
    });

    // ── KELOLA RTPU ───────────────────────────────────────────────────────────
    Route::middleware('role:rtpu')->prefix('rtpu')->name('rtpu.')->group(function () {
        // Verifikasi akun eksternal
        Route::post('/verifikasi/{id}/setujui', [RtpuController::class, 'setujuiAkun'])->name('verifikasi.setujui');
        Route::post('/verifikasi/{id}/tolak',   [RtpuController::class, 'tolakAkun'])->name('verifikasi.tolak');
        // Publikasi inovasi
        Route::get('/publikasi/{id}',           [RtpuController::class, 'formPublikasi'])->name('publikasi.form');
        Route::post('/publikasi/{id}',          [RtpuController::class, 'publikasi'])->name('publikasi.simpan');
        // Penjadwalan jasa
        Route::get('/jadwal/{detailId}/form',   [RtpuController::class, 'formJadwal'])->name('jadwal.form');
        Route::post('/jadwal/{detailId}',       [RtpuController::class, 'simpanJadwal'])->name('jadwal.simpan');
        Route::post('/jadwal/{jadwalId}/selesai',[RtpuController::class, 'selesaiJadwal'])->name('jadwal.selesai');
    });

    // ── MARKETPLACE (Eksternal - browse saja, tanpa checkout) ─────────────────
    Route::middleware('role:eksternal')->prefix('marketplace')->name('marketplace.')->group(function () {
        Route::get('/',     [MarketplaceController::class, 'index'])->name('index');
        Route::get('/{id}', [MarketplaceController::class, 'show'])->name('show');
    });
});
