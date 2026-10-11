<?php

use App\Http\Controllers\BarangController;
fitur-transfer-aset
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\UserManagementController;
main

// Redirect halaman utama langsung ke halaman Transfer milikmu untuk pengujian
Route::get('/', function () {
    return redirect()->route('transfer.index');
});

 fitur-transfer-aset
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

// Route Dashboard
Route::get('/dashboard', [BarangController::class, 'dashboard'])->name('dashboard');

// Route Transfer & Riwayat
Route::get('/transfer-aset', [BarangController::class, 'riwayatTransfer'])->name('barang.riwayat_transfer');

// ============================================================
// ROUTE CUSTOM BARANG (WAJIB DI ATAS RESOURCE)
// ============================================================
Route::get('/barang/export-excel', [BarangController::class, 'exportExcel'])->name('barang.export.excel');
Route::get('/barang/trashed', [BarangController::class, 'trashed'])->name('barang.trashed');
Route::post('/barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');

// Route CRUD Data Aset (Resource Standard)
Route::resource('barang', BarangController::class);

// ============================================================
// ROUTE MENU BARU (SETTINGS, MASTER DATA, MAINTENANCE, USERS)
// ============================================================

// Settings
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

// Master Data
Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data.index');

// Maintenance / Pemeliharaan
Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');

// Manajemen Pengguna
Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
main
