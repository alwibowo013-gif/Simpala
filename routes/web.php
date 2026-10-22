<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GantiRugiController;
use App\Http\Controllers\JelajahController;
use App\Http\Controllers\KategoriAlatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\VerifikasiController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});

// ===== Profil: bisa diakses SEMUA user yang sudah login (Admin & Siswa) =====
// Cukup middleware 'auth' (tanpa 'role:...') karena tiap user hanya mengelola profilnya sendiri.
Route::middleware('auth')->prefix('profil')->name('profil.')->group(function () {
    Route::get('/', [ProfilController::class, 'edit'])->name('edit');
    Route::put('/info', [ProfilController::class, 'updateInfo'])->name('info');
    Route::post('/foto', [ProfilController::class, 'updateFoto'])->name('foto');
    Route::delete('/foto', [ProfilController::class, 'hapusFoto'])->name('foto.hapus');
    Route::put('/password', [ProfilController::class, 'updatePassword'])->name('password');
});

// ===== Area khusus role Siswa =====
Route::middleware(['auth', 'role:siswa'])->group(function () {
    // SESUDAH:
Route::get('/dashboard', [DashboardController::class, 'siswa'])->name('dashboard');

    Route::get('/jelajah', [JelajahController::class, 'index'])->name('jelajah');

    Route::get('/pinjam/create', [PeminjamanController::class, 'create'])->name('pinjam.create');
    Route::post('/pinjam', [PeminjamanController::class, 'store'])->name('pinjam.store');
    Route::get('/pinjam-saya', [PeminjamanController::class, 'mine'])->name('pinjam.saya');
});

// ===== Area khusus role Admin =====
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    Route::resource('kategori', KategoriAlatController::class);
    Route::resource('alat', AlatController::class);

    Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::post('/verifikasi/{peminjaman}/approve', [VerifikasiController::class, 'approve'])->name('verifikasi.approve');
    Route::post('/verifikasi/{peminjaman}/reject', [VerifikasiController::class, 'reject'])->name('verifikasi.reject');

    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::post('/pengembalian/{peminjaman}/proses', [PengembalianController::class, 'proses'])->name('pengembalian.proses');

    Route::get('/ganti-rugi', [GantiRugiController::class, 'index'])->name('gantirugi.index');
    Route::post('/ganti-rugi/{detail}/lunas', [GantiRugiController::class, 'tandaiLunas'])->name('gantirugi.lunas');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export/csv', [LaporanController::class, 'exportCsv'])->name('laporan.export.csv');

    Route::resource('pengguna', PenggunaController::class);
});
