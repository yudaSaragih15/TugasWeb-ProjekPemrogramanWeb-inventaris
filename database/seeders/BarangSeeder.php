<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run()
    {
        Barang::create([
            'kode_barang' => 'LAB-PC-001',
            'nama_barang' => 'PC Desktop i7 16GB',
            'kategori'    => 'Elektronik',
            'lokasi'      => 'Lab Komputer 1 FMIPA',
            'jumlah'      => 25,
            'kondisi'     => 'Baik',
        ]);

        Barang::create([
            'kode_barang' => 'INF-PROJ-002',
            'nama_barang' => 'Proyektor Epson EB-E500',
            'kategori'    => 'Elektronik',
            'lokasi'      => 'Ruang Kuliah A3',
            'jumlah'      => 5,
            'kondisi'     => 'Baik',
        ]);

        Barang::create([
            'kode_barang' => 'FURN-MEJA-010',
            'nama_barang' => 'Meja Dosen Kayu Jati',
            'kategori'    => 'Furniture',
            'lokasi'      => 'Ruang Dosen Matematika',
            'jumlah'      => 12,
            'kondisi'     => 'Rusak Ringan',
        ]);
    }
}