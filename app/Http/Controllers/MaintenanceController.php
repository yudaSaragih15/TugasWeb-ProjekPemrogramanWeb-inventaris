<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use App\Models\Barang;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Menampilkan Halaman Index Pemeliharaan
     */
    public function index()
    {
        // Mengambil data pemeliharaan terbaru beserta relasi barangnya
        $records = Pemeliharaan::with('barang')->latest()->paginate(10);
        
        // Mengambil semua barang untuk dropdown form input
        $barangs = Barang::orderBy('nama_barang')->get();

        return view('maintenance.index', compact('records', 'barangs'));
    }

    /**
     * Menyimpan Data Pemeliharaan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id'           => 'required|exists:barang,id',
            'tanggal_servis'      => 'required|date',
            'jenis_layanan'       => 'required|string',
            'deskripsi_kerusakan' => 'required|string',
            'tindakan_perbaikan'  => 'nullable|string',
            'biaya'               => 'required|numeric|min:0',
            'status'              => 'required|in:Selesai,Dalam Proses,Menunggu Sparepart',
        ]);

        Pemeliharaan::create($request->all());

        return redirect()->route('maintenance.index')->with('success', 'Catatan servis berhasil disimpan.');
    }

    /**
     * Update Status Pemeliharaan (Quick Action)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Selesai,Dalam Proses,Menunggu Sparepart'
        ]);

        $record = Pemeliharaan::findOrFail($id);
        $record->update(['status' => $request->status]);

        return back()->with('success', 'Status pemeliharaan diperbarui menjadi ' . $request->status);
    }

    /**
     * Hapus Data Pemeliharaan
     */
    public function destroy($id)
    {
        $record = Pemeliharaan::findOrFail($id);
        $record->delete();

        return back()->with('success', 'Data pemeliharaan berhasil dihapus.');
    }
}