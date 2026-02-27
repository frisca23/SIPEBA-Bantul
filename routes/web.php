<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PenguranganController;
use App\Http\Controllers\RekapSetdaController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Public route - Redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Master Data
    Route::get('barang/{barang}/kartu', [BarangController::class, 'kartu'])->name('barang.kartu');
    Route::resource('barang', BarangController::class);
    Route::resource('jenis-barang', JenisBarangController::class);

    // Transactions
    Route::resource('penerimaan', PenerimaanController::class);
    Route::post('/penerimaan/{penerimaan}/approve', [PenerimaanController::class, 'approve'])
        ->name('penerimaan.approve');

    Route::resource('pengurangan', PenguranganController::class);
    Route::post('/pengurangan/{pengurangan}/approve', [PenguranganController::class, 'approve'])
        ->name('pengurangan.approve');

    Route::resource('stock-opname', StockOpnameController::class);
    Route::post('/stock-opname/{stockOpname}/approve', [StockOpnameController::class, 'approve'])
        ->name('stock-opname.approve');

    // Laporan (Reports)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::post('/buku-penerimaan', [LaporanController::class, 'bukuPenerimaan'])->name('buku-penerimaan');
        Route::post('/buku-pengurangan', [LaporanController::class, 'bukuPengurangan'])->name('buku-pengurangan');
        Route::post('/hasil-fisik-stock-opname', [LaporanController::class, 'hasilFisikStockOpname'])->name('hasil-fisik-stock-opname');
        Route::post('/berita-acara-pemantauan', [LaporanController::class, 'beritaAcaraPemantauan'])->name('berita-acara-pemantauan');
        Route::post('/rekonsiliasi', [LaporanController::class, 'rekonsiliasi'])->name('rekonsiliasi');
    });

    // Rekap SETDA (Super Admin only)
    Route::get('/rekap-setda', [RekapSetdaController::class, 'index'])
        ->name('rekap-setda.index');

    // User Management (Super Admin only)
    Route::resource('users', UserController::class)->except(['show']);
});
