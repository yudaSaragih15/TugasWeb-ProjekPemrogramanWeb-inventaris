<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatTransfer;

class DashboardController extends Controller
{
   public function index()
    {
        // Statistik utama
        $totalBarang = Barang::count();
        $totalJumlah = Barang::sum('jumlah');
        
        $barangTerbaru = Barang::latest()->take(5)->get();
        $riwayat_terbaru = RiwayatTransfer::with('barang')->latest()->take(5)->get();

        // Data Grafik Berdasarkan Kondisi
        $kondisiBaik = Barang::where('kondisi', 'LIKE', '%baik%')
                             ->orWhere('kondisi', 'LIKE', '%Baik%')
                             ->sum('jumlah');
                             
        $kondisiRingan = Barang::where('kondisi', 'LIKE', '%rusak ringan%')
                               ->orWhere('kondisi', 'LIKE', '%Rusak Ringan%')
                               ->sum('jumlah');
                               
        $kondisiBerat = Barang::where('kondisi', 'LIKE', '%rusak berat%')
                              ->orWhere('kondisi', 'LIKE', '%Rusak Berat%')
                              ->sum('jumlah');

        return view('dashboard', compact(
            'totalBarang', 
            'totalJumlah',
            'barangTerbaru',
            'riwayat_terbaru',
            'kondisiBaik',
            'kondisiRingan',
            'kondisiBerat'
        ));
    }
}