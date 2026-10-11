<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatTransfer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TransferController extends Controller
{
    /**
     * Menampilkan formulir transfer aset dan data audit trail log.
     */
    public function index(Request $request): View
    {
        // 1. Ambil daftar barang aktif untuk dropdown pilihan aset
        $barangs = Barang::select('id', 'kode_barang', 'nama_barang', 'kategori', 'lokasi', 'jumlah', 'kondisi')
            ->orderBy('nama_barang', 'asc')
            ->get();

        // 2. Query riwayat mutasi dengan eager load relasi barang (termasuk yang di-soft delete)
        $query = RiwayatTransfer::with(['barang' => function ($q) {
            $q->withTrashed();
        }])->latest('id');

        // Fitur pencarian riwayat
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($sub) use ($search) {
                    $sub->where('kode_barang', 'like', "%{$search}%")
                        ->orWhere('nama_barang', 'like', "%{$search}%");
                })
                ->orWhere('lokasi_asal', 'like', "%{$search}%")
                ->orWhere('lokasi_tujuan', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $riwayatTransfers = $query->paginate(15)->withQueryString();

        // Opsi lokasi laboratorium & unit FMIPA UNIMED
        $lokasiList = [
            'Lab Fisika Dasar (Lt. 1 Gedung Fisika)',
            'Lab Fisika Modern & Elektronika (Lt. 2 Gedung Fisika)',
            'Lab Kimia Anorganik & Analitik (Lt. 1 Gedung Kimia)',
            'Lab Kimia Organik & Biokimia (Lt. 2 Gedung Kimia)',
            'Lab Biologi Umum & Mikrobiologi (Lt. 1 Gedung Biologi)',
            'Lab Genetika & Ekologi (Lt. 2 Gedung Biologi)',
            'Lab Komputasi Matematika (Lt. 3 Gedung Matematika)',
            'Lab Multimedia & Statistika (Lt. 2 Gedung Matematika)',
            'Gudang Sarpras Dekanat FMIPA (Gedung Utama)',
            'Ruang Dosen & Seminar FMIPA (Lt. 2 Dekanat)',
        ];

        return view('transfer.index', compact('barangs', 'riwayatTransfers', 'lokasiList'));
    }

    /**
     * Memproses mutasi perpindahan fisik aset dalam Database Transaction.
     */
    public function store(Request $request): RedirectResponse
    {
        // Cek nama tabel secara dinamis agar aman di semua lingkungan
        $barangTable = Schema::hasTable('barang') ? 'barang' : 'barangs';

        // 1. Validasi Input Form
        $validated = $request->validate([
            'barang_id'        => ['required', "exists:{$barangTable},id"],
            'lokasi_tujuan'    => ['required', 'string', 'max:150'],
            'jumlah'           => ['nullable', 'integer', 'min:1'],
            'tanggal'          => ['nullable', 'date'],
            'tanggal_transfer' => ['nullable', 'date'],
            'keterangan'       => ['nullable', 'string', 'max:1000'],
        ], [
            'barang_id.required'    => 'Silakan pilih aset barang yang akan dimutasi.',
            'barang_id.exists'      => 'Data barang tidak ditemukan dalam database inventaris.',
            'lokasi_tujuan.required' => 'Pilih atau tentukan lokasi tujuan mutasi aset.',
            'jumlah.min'            => 'Jumlah unit yang dipindahkan minimal 1.',
        ]);

        $tanggalMutasi = $request->input('tanggal') 
            ?? $request->input('tanggal_transfer') 
            ?? now()->toDateString();

        // 2. Eksekusi Atomik dalam DB::transaction
        try {
            DB::transaction(function () use ($validated, $tanggalMutasi, $request) {
                // a. Ambil data asli barang dan kunci baris untuk konsistensi
                $barang = Barang::lockForUpdate()->findOrFail($validated['barang_id']);
                $lokasiAsal = $barang->lokasi;
                $targetLokasi = trim($validated['lokasi_tujuan']);

                // Validasi Aturan Bisnis 1: Lokasi tujuan tidak boleh sama dengan lokasi asal
                if (trim($lokasiAsal) === $targetLokasi) {
                    throw new \InvalidArgumentException(
                        "Lokasi tujuan tidak boleh sama dengan lokasi penempatan asal saat ini ({$lokasiAsal})."
                    );
                }

                // Tentukan jumlah unit yang dimutasi
                $jumlahTransfer = !empty($validated['jumlah']) 
                    ? (int)$validated['jumlah'] 
                    : (int)($barang->jumlah ?? 1);

                // Validasi Aturan Bisnis 2: Jumlah transfer tidak boleh melebihi stok fisik
                if ($barang->jumlah && $jumlahTransfer > $barang->jumlah) {
                    throw new \InvalidArgumentException(
                        "Jumlah yang dipindahkan ({$jumlahTransfer} unit) melebihi total unit fisik yang ada ({$barang->jumlah} unit)."
                    );
                }

                // b. Simpan riwayat mutasi baru ke tabel riwayat_transfers (sesuai schema Yuda)
                RiwayatTransfer::create([
                    'barang_id'     => $barang->id,
                    'lokasi_asal'   => $lokasiAsal,
                    'lokasi_tujuan' => $targetLokasi,
                    'jumlah'        => $jumlahTransfer,
                    'keterangan'    => $validated['keterangan'] ?? null,
                    'tanggal'       => $tanggalMutasi,
                ]);

                // c. Perbarui lokasi fisik master barang ke lokasi tujuan
                $barang->update([
                    'lokasi' => $targetLokasi,
                ]);
            });

            return redirect()
                ->route('transfer.index')
                ->with('success', 'Mutasi aset berhasil dicatat! Lokasi fisik barang telah diperbarui.');

        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['lokasi_tujuan' => $e->getMessage()]);

        } catch (\Exception $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memproses transaksi mutasi: ' . $e->getMessage());
        }
    }
}