<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes - Development Mode
|--------------------------------------------------------------------------
*/

// Redirect Home ke Data Aset
Route::get('/', function () {
    return redirect()->route('barang.index'); 
});

// Route Transfer (Punya Teman)
Route::get('/transfer', [TransferController::class, 'index'])->name('transfer.index');
Route::post('/transfer', [TransferController::class, 'store'])->name('transfer.store');
Route::get('/transfer-aset', [TransferController::class, 'index'])->name('barang.riwayat_transfer');

// Route Dashboard & Data Aset
Route::get('/dashboard', [BarangController::class, 'dashboard'])->name('dashboard');
Route::get('/barang/export-excel', [BarangController::class, 'exportExcel'])->name('barang.export.excel');
Route::get('/barang/trashed', [BarangController::class, 'trashed'])->name('barang.trashed');
Route::post('/barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
Route::resource('barang', BarangController::class);

// Route Master Data
Route::prefix('master-data')->name('master-data.')->group(function () {
    Route::get('/', [MasterDataController::class, 'index'])->name('index');
    Route::post('/lokasi', [MasterDataController::class, 'storeLokasi'])->name('lokasi.store');
    Route::delete('/lokasi/{id}', [MasterDataController::class, 'destroyLokasi'])->name('lokasi.destroy');
    Route::post('/kategori', [MasterDataController::class, 'storeKategori'])->name('kategori.store');
    Route::delete('/kategori/{id}', [MasterDataController::class, 'destroyKategori'])->name('kategori.destroy');
});

// Route Maintenance / Pemeliharaan (BARU)
Route::prefix('maintenance')->name('maintenance.')->group(function () {
    Route::get('/', [MaintenanceController::class, 'index'])->name('index');
    Route::post('/', [MaintenanceController::class, 'store'])->name('store');
    Route::patch('/{id}/status', [MaintenanceController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{id}', [MaintenanceController::class, 'destroy'])->name('destroy');
});

// Route Settings & Users
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');

// Dummy Login
Route::get('/login', function () {
    return "Sistem Autentikasi / Login sedang dalam tahap pengembangan.";
})->name('login');