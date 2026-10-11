<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect '/' langsung ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route khusus Dashboard
Route::get('/dashboard', [BarangController::class, 'dashboard'])->name('dashboard');

// Route khusus Transfer & Riwayat
Route::get('/transfer-aset', [BarangController::class, 'riwayatTransfer'])->name('barang.riwayat_transfer');

// ============================================================
// PENTING: Route Custom HARUS di atas Route::resource
// Agar tidak tertangkap oleh parameter {barang} milik resource
// ============================================================

// Route Export Excel (Custom)
Route::get('/barang/export-excel', [BarangController::class, 'exportExcel'])
    ->name('barang.export.excel');

// Route Restore Soft Delete (Custom)
Route::post('/barang/{id}/restore', [BarangController::class, 'restore'])
    ->name('barang.restore');

// Route CRUD Data Aset Barang (Resource Standard)
// Ini akan menangani: index, create, store, show, edit, update, destroy
Route::resource('barang', BarangController::class);