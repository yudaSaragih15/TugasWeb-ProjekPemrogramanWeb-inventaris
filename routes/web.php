<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

// Redirect halaman utama langsung ke halaman Transfer milikmu untuk pengujian
Route::get('/', function () {
    return redirect()->route('transfer.index');
});

// Modul Transfer & Riwayat Aset (Dikeluarkan sementara dari middleware auth)
Route::get('/transfer', [TransferController::class, 'index'])->name('transfer.index');
Route::post('/transfer', [TransferController::class, 'store'])->name('transfer.store');
Route::get('/transfer-aset', [TransferController::class, 'index'])->name('barang.riwayat_transfer');

// Rute lain milik Yuda
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [BarangController::class, 'dashboard'])->name('dashboard');
    Route::get('/barang/export-excel', [BarangController::class, 'exportExcel'])->name('barang.export.excel');
    Route::post('/barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
    Route::resource('barang', BarangController::class);
});

// Dummy route login
Route::get('/login', function () {
    return "Sistem Autentikasi / Login sedang dalam tahap pengembangan.";
})->name('login');