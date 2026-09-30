<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatTransfer;

class DashboardController extends Controller
{
    public function index()
    {
        $total_aset = Barang::sum('jumlah');
        $total_jenis = Barang::count();
        $kondisi_baik = Barang::where('kondisi', 'Baik')->sum('jumlah');
        $kondisi_rusak = Barang::where('kondisi', '!=', 'Baik')->sum('jumlah');
        
        $barang_terbaru = Barang::latest()->take(5)->get();
        $riwayat_terbaru = RiwayatTransfer::with('barang')->latest()->take(5)->get();

        return view('dashboard', compact(
            'total_aset', 
            'total_jenis',
            'kondisi_baik', 
            'kondisi_rusak', 
            'barang_terbaru',
            'riwayat_terbaru'
        ));
    }
}