<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel sebelum diisi data baru
        Barang::truncate();

        $dataset = [
            [
                'kode_barang' => 'LAB-PC-001',
                'nama_barang' => 'PC Desktop Intel i7 16GB RAM',
                'kategori'    => 'Elektronik',
                'lokasi'      => 'Lab Komputer 1 FMIPA',
                'jumlah'      => 25,
                'kondisi'     => 'Baik',
                'harga_satuan'=> 12500000,
                'tanggal_pengadaan' => '2024-02-15',
                'sumber_dana' => 'DIPA Universitas',
                'garansi_exp' => '2026-02-15',
                'pj_ruangan'  => 'Dr. Herman, M.Kom'
            ],
            [
                'kode_barang' => 'BIO-MIC-012',
                'nama_barang' => 'Mikroskop Binokuler Olympus CX23',
                'kategori'    => 'Alat Laboratorium',
                'lokasi'      => 'Lab Biologi Dasar',
                'jumlah'      => 15,
                'kondisi'     => 'Baik',
                'harga_satuan'=> 18500000,
                'tanggal_pengadaan' => '2023-08-10',
                'sumber_dana' => 'Hibah Penelitian Dekanat',
                'garansi_exp' => '2025-08-10',
                'pj_ruangan'  => 'Prof. Dr. Ratna, M.Si'
            ],
            [
                'kode_barang' => 'KIM-SPEC-003',
                'nama_barang' => 'Spectrophotometer UV-Vis Genesis',
                'kategori'    => 'Alat Laboratorium',
                'lokasi'      => 'Lab Kimia Instrumen',
                'jumlah'      => 2,
                'kondisi'     => 'Rusak Ringan',
                'harga_satuan'=> 65000000,
                'tanggal_pengadaan' => '2022-11-20',
                'sumber_dana' => 'Bantuan Kementerian',
                'garansi_exp' => '2024-11-20',
                'pj_ruangan'  => 'Drs. Ahmad, M.Si'
            ],
            [
                'kode_barang' => 'INF-PROJ-005',
                'nama_barang' => 'Proyektor Epson EB-E500 3300 Lumens',
                'kategori'    => 'Elektronik',
                'lokasi'      => 'Ruang Kuliah A3',
                'jumlah'      => 8,
                'kondisi'     => 'Baik',
                'harga_satuan'=> 6200000,
                'tanggal_pengadaan' => '2024-01-05',
                'sumber_dana' => 'DIPA Universitas',
                'garansi_exp' => '2026-01-05',
                'pj_ruangan'  => 'Budi Santoso, S.T.'
            ],
            [
                'kode_barang' => 'FURN-MEJA-044',
                'nama_barang' => 'Meja Dosen Kayu Jati Minimalis',
                'kategori'    => 'Furniture',
                'lokasi'      => 'Ruang Dosen Matematika',
                'jumlah'      => 12,
                'kondisi'     => 'Rusak Ringan',
                'harga_satuan'=> 2500000,
                'tanggal_pengadaan' => '2021-05-12',
                'sumber_dana' => 'DIPA Fakultas',
                'garansi_exp' => '2022-05-12',
                'pj_ruangan'  => 'Sekretariat Matematika'
            ],
            [
                'kode_barang' => 'FIS-OSC-002',
                'nama_barang' => 'Oscilloscope Digital Rigol DS1054Z',
                'kategori'    => 'Alat Laboratorium',
                'lokasi'      => 'Lab Fisika Modern',
                'jumlah'      => 6,
                'kondisi'     => 'Baik',
                'harga_satuan'=> 8900000,
                'tanggal_pengadaan' => '2023-03-18',
                'sumber_dana' => 'Hibah Kompetisi',
                'garansi_exp' => '2025-03-18',
                'pj_ruangan'  => 'Dr. Hendra, M.Si'
            ],
            [
                'kode_barang' => 'FAS-AC-010',
                'nama_barang' => 'AC Split Daikin 2 PK Inverter',
                'kategori'    => 'Fasilitas Ruangan',
                'lokasi'      => 'Ruang Seminar FMIPA',
                'jumlah'      => 4,
                'kondisi'     => 'Rusak Berat',
                'harga_satuan'=> 9200000,
                'tanggal_pengadaan' => '2020-09-01',
                'sumber_dana' => 'DIPA Universitas',
                'garansi_exp' => '2023-09-01',
                'pj_ruangan'  => 'Pengelola Gedung Utama'
            ]
        ];

        foreach ($dataset as $data) {
            Barang::create($data);
        }
    }
}