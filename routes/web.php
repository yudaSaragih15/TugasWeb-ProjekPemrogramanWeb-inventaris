<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes - Full & Fixed
|--------------------------------------------------------------------------
*/

// 1. ROUTE PUBLIK (Login & Logout)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    if (auth()->check()) return redirect()->route('dashboard');
    return redirect()->route('login');
});

// 2. GRUP ROUTE TERPROTEKSI (Wajib Login)
Route::middleware(['auth', \App\Http\Middleware\CheckFakultas::class])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- DATA ASET (BARANG) ---
    Route::get('/barang/export-excel', [BarangController::class, 'exportExcel'])->name('barang.export.excel');
    Route::get('/barang/trashed', [BarangController::class, 'trashed'])->name('barang.trashed');
    
    // Route khusus untuk aksi arsip (Restore & Force Delete)
    Route::post('/barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
    Route::delete('/barang/{id}/force-delete', [BarangController::class, 'forceDelete'])->name('barang.forceDelete');
    
    // Resource route standar (index, create, store, edit, update, destroy)
    Route::resource('barang', BarangController::class);

    // --- TRANSFER & RIWAYAT ---
    Route::get('/transfer', [TransferController::class, 'index'])->name('transfer.index');
    Route::post('/transfer', [TransferController::class, 'store'])->name('transfer.store');
    Route::get('/transfer-aset', [TransferController::class, 'index'])->name('barang.riwayat_transfer');

    // --- MASTER DATA (HANYA SUPER ADMIN) ---
    Route::middleware([\App\Http\Middleware\IsSuperAdmin::class])->prefix('master-data')->name('master-data.')->group(function () {
        Route::get('/', [MasterDataController::class, 'index'])->name('index');
        Route::post('/lokasi', [MasterDataController::class, 'storeLokasi'])->name('lokasi.store');
        Route::delete('/lokasi/{id}', [MasterDataController::class, 'destroyLokasi'])->name('lokasi.destroy');
        Route::post('/kategori', [MasterDataController::class, 'storeKategori'])->name('kategori.store');
        Route::delete('/kategori/{id}', [MasterDataController::class, 'destroyKategori'])->name('kategori.destroy');
    });

    // --- PEMELIHARAAN (MAINTENANCE) ---
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::post('/', [MaintenanceController::class, 'store'])->name('store');
        Route::patch('/{id}/status', [MaintenanceController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [MaintenanceController::class, 'destroy'])->name('destroy');
    });

    // --- PENGATURAN (SETTINGS) ---
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // --- MANAJEMEN PENGGUNA (HANYA SUPER ADMIN) ---
    Route::middleware([\App\Http\Middleware\IsSuperAdmin::class])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
    });
});