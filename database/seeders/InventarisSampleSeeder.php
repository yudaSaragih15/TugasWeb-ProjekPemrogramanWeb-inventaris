<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\RiwayatTransfer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InventarisSampleSeeder extends Seeder
{
    /**
     * Run the database seeds for FMIPA UNIMED.
     */
    public function run(): void
    {
        // 1. Matikan Foreign Key check sementara agar truncate tabel aman
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $barangTable = Schema::hasTable('barang') ? 'barang' : 'barangs';
        DB::table($barangTable)->truncate();
        DB::table('riwayat_transfers')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seeding Data Master Barang FMIPA UNIMED
        $b1 = Barang::create([
            'kode_barang' => 'AST-FSP-2024-001',
            'nama_barang' => 'Osiloskop Digital Rigol DS1054Z 50MHz',
            'kategori'    => 'Alat Laboratorium',
            'jumlah'      => 6,
            'kondisi'     => 'Baik',
            'lokasi'      => 'Lab Fisika Modern & Elektronika (Lt. 2 Gedung Fisika)',
            'foto'        => null,
        ]);

        $b2 = Barang::create([
            'kode_barang' => 'AST-BIO-2024-012',
            'nama_barang' => 'Mikroskop Binokuler Olympus CX23 LED',
            'kategori'    => 'Alat Laboratorium',
            'jumlah'      => 14,
            'kondisi'     => 'Baik',
            'lokasi'      => 'Lab Biologi Umum & Mikrobiologi (Lt. 1 Gedung Biologi)',
            'foto'        => null,
        ]);

        $b3 = Barang::create([
            'kode_barang' => 'AST-KMA-2024-045',
            'nama_barang' => 'Spektrofotometer UV-Vis Shimadzu UV-1280',
            'kategori'    => 'Alat Laboratorium',
            'jumlah'      => 2,
            'kondisi'     => 'Baik',
            'lokasi'      => 'Lab Kimia Anorganik & Analitik (Lt. 1 Gedung Kimia)',
            'foto'        => null,
        ]);

        $b4 = Barang::create([
            'kode_barang' => 'AST-MAT-2024-089',
            'nama_barang' => 'Workstation Komputasi Intel Core i9-14900K 64GB',
            'kategori'    => 'Perangkat IT',
            'jumlah'      => 18,
            'kondisi'     => 'Baik',
            'lokasi'      => 'Lab Komputasi Matematika (Lt. 3 Gedung Matematika)',
            'foto'        => null,
        ]);

        // 3. Seeding Data Riwayat Mutasi (Sesuai kolom schema Yuda: jumlah & tanggal)
        RiwayatTransfer::create([
            'barang_id'     => $b1->id,
            'lokasi_asal'   => 'Gudang Sarpras Dekanat FMIPA (Gedung Utama)',
            'lokasi_tujuan' => 'Lab Fisika Modern & Elektronika (Lt. 2 Gedung Fisika)',
            'jumlah'        => 2,
            'tanggal'       => now()->subDays(5),
            'keterangan'    => 'Distribusi pengadaan sarana praktikum gelombang & elektronika semester ganjil T.A. 2026/2027.',
        ]);

        RiwayatTransfer::create([
            'barang_id'     => $b2->id,
            'lokasi_asal'   => 'Gudang Sarpras Dekanat FMIPA (Gedung Utama)',
            'lokasi_tujuan' => 'Lab Biologi Umum & Mikrobiologi (Lt. 1 Gedung Biologi)',
            'jumlah'        => 4,
            'tanggal'       => now()->subDays(2),
            'keterangan'    => 'Penempatan unit mikroskop tambahan untuk praktikum Anatomi Tumbuhan & Fisiologi Hewan.',
        ]);
    }
}