<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;

// Dashboard Utama
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Manajemen Data Aset
Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

// Transfer Aset & Riwayat
Route::post('/barang/{id}/transfer', [BarangController::class, 'transfer'])->name('barang.transfer');
Route::get('/transfer-aset', [BarangController::class, 'riwayatTransfer'])->name('barang.riwayat_transfer');