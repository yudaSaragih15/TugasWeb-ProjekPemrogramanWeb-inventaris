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
| Web Routes - VERSI DEVELOPMENT (TANPA AUTH AGAR BISA CEK TAMPILAN)
|--------------------------------------------------------------------------
*/

// 1. UBAH REDIRECT HOME: Sekarang langsung ke Data Aset (Punya Kamu)
Route::get('/', function () {
    return redirect()->route('barang.index'); 
});

// 2. ROUTE TRANSFER (Punya Teman - Tetap Jalan)
Route::get('/transfer', [TransferController::class, 'index'])->name('transfer.index');
Route::post('/transfer', [TransferController::class, 'store'])->name('transfer.store');
Route::get('/transfer-aset', [TransferController::class, 'index'])->name('barang.riwayat_transfer');

// 3. ROUTE DASHBOARD & DATA ASET (Punya Kamu - TANPA AUTH SEMENTARA)
Route::get('/dashboard', [BarangController::class, 'dashboard'])->name('dashboard');

Route::get('/barang/export-excel', [BarangController::class, 'exportExcel'])->name('barang.export.excel');
Route::get('/barang/trashed', [BarangController::class, 'trashed'])->name('barang.trashed');
Route::post('/barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
Route::resource('barang', BarangController::class);

// 4. ROUTE MENU BARU (Punya Kamu - TANPA AUTH SEMENTARA)
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data.index');
Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');

// 5. DUMMY LOGIN (Biarkan saja, tidak mengganggu karena kita bypass auth)
Route::get('/login', function () {
    return "Sistem Autentikasi / Login sedang dalam tahap pengembangan.";
})->name('login');