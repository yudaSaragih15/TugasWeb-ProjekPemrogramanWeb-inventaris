<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatTransfer;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // 1. Ambil daftar barang
        $barangQuery = Barang::select('id', 'kode_barang', 'nama_barang', 'lokasi', 'jumlah');
        if (Schema::hasColumn('barang', 'fakultas')) {
            $barangQuery->where('fakultas', $user->fakultas);
        }
        $barangs = $barangQuery->orderBy('nama_barang')->get();

        // 2. Query riwayat mutasi
        $query = RiwayatTransfer::with(['barang' => function ($q) { $q->withTrashed(); }])->latest('id');
        
        // SAFE CHECK: Filter riwayat hanya jika kolom fakultas ada
        if (Schema::hasColumn('riwayat_transfers', 'fakultas')) {
            $query->where('fakultas', $user->fakultas);
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($sub) use ($search) {
                    $sub->where('kode_barang', 'like', "%{$search}%")->orWhere('nama_barang', 'like', "%{$search}%");
                })
                ->orWhere('lokasi_asal', 'like', "%{$search}%")
                ->orWhere('lokasi_tujuan', 'like', "%{$search}%");
            });
        }

        $riwayatTransfers = $query->paginate(15)->withQueryString();
        $lokasiList = Lokasi::orderBy('nama_lokasi')->pluck('nama_lokasi')->toArray();

        return view('transfer.index', compact('barangs', 'riwayatTransfers', 'lokasiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'lokasi_tujuan' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $barang = Barang::lockForUpdate()->findOrFail($validated['barang_id']);
                $lokasiAsal = $barang->lokasi;
                $targetLokasi = trim($validated['lokasi_tujuan']);

                if (trim($lokasiAsal) === $targetLokasi) {
                    throw new \InvalidArgumentException("Lokasi tujuan tidak boleh sama dengan lokasi asal.");
                }

                $dataCreate = [
                    'barang_id' => $barang->id,
                    'lokasi_asal' => $lokasiAsal,
                    'lokasi_tujuan' => $targetLokasi,
                    'keterangan' => $validated['keterangan'] ?? null,
                    'tanggal' => now()->toDateString(),
                ];

                // Simpan fakultas jika kolom tersedia
                if (Schema::hasColumn('riwayat_transfers', 'fakultas')) {
                    $dataCreate['fakultas'] = auth()->user()->fakultas;
                }

                RiwayatTransfer::create($dataCreate);
                $barang->update(['lokasi' => $targetLokasi]);
            });

            return redirect()->route('transfer.index')->with('success', 'Mutasi aset berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses transfer: ' . $e->getMessage());
        }
    }
}