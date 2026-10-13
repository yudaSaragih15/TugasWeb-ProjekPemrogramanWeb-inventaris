<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = ($user->role === 'super_admin');

        // 1. TENTUKAN BASE QUERY BERDASARKAN ROLE
        if ($isSuperAdmin) {
            // Super Admin melihat SEMUA data (Tanpa filter fakultas)
            $baseQuery = Barang::query();
            $subtitle = "Ringkasan data inventaris aset seluruh kampus UNIMED.";
        } else {
            // Admin Fakultas hanya melihat data fakultasnya sendiri
            $baseQuery = Barang::where('fakultas', $user->fakultas);
            $subtitle = "Ringkasan data inventaris aset kampus " . $user->fakultas . ".";
        }

        // 2. HITUNG STATISTIK MENGGUNAKAN BASE QUERY
        $totalJenisAset = (clone $baseQuery)->count();
        $totalUnitBarang = (clone $baseQuery)->sum('jumlah') ?? 0;

        // 3. DATA GRAFIK KONDISI
        $kondisiStats = [
            'Baik'          => (clone $baseQuery)->where('kondisi', 'Baik')->count(),
            'Rusak Ringan'  => (clone $baseQuery)->where('kondisi', 'Rusak Ringan')->count(),
            'Rusak Berat'   => (clone $baseQuery)->where('kondisi', 'Rusak Berat')->count(),
        ];

        // 4. ASET TERBARU (5 Terakhir)
        $asetTerbaru = (clone $baseQuery)->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalJenisAset', 
            'totalUnitBarang', 
            'kondisiStats', 
            'asetTerbaru',
            'subtitle' // Kirim subtitle dinamis ke view
        ));
    }
}