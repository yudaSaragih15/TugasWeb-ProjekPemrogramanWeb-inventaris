<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk debug

class MaintenanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Pemeliharaan::with('barang');

        if (Schema::hasColumn('pemeliharaans', 'fakultas')) {
            $query->where('fakultas', $user->fakultas);
        }

        $records = $query->latest()->paginate(10);
        
        $barangQuery = Barang::query();
        if (Schema::hasColumn('barang', 'fakultas')) {
            $barangQuery->where('fakultas', $user->fakultas);
        }
        $barangs = $barangQuery->orderBy('nama_barang')->get();

        return view('maintenance.index', compact('records', 'barangs'));
    }

    public function store(Request $request)
    {
        // Debug: Lihat data apa yang masuk (Hapus baris ini setelah berhasil)
        // dd($request->all()); 

        $validated = $request->validate([
            'barang_id'           => 'required|exists:barang,id',
            'tanggal_servis'      => 'required|date',
            'jenis_layanan'       => 'required|string',
            'deskripsi_kerusakan' => 'required|string',
            'tindakan_perbaikan'  => 'nullable|string',
            'biaya'               => 'required|numeric|min:0',
            // Hapus validasi status jika tidak ada input status di form HTML
            // 'status'              => 'required|in:Selesai,Dalam Proses,Menunggu Sparepart',
        ]);

        try {
            $data = $validated;
            
            // Set default status jika tidak ada di form
            $data['status'] = $request->input('status', 'Dalam Proses');
            
            // Isi default tindakan perbaikan
            if (empty($data['tindakan_perbaikan'])) {
                $data['tindakan_perbaikan'] = $data['deskripsi_kerusakan'];
            }

            // Simpan fakultas jika kolom tersedia
            if (Schema::hasColumn('pemeliharaans', 'fakultas')) {
                $data['fakultas'] = auth()->user()->fakultas;
            }

            Pemeliharaan::create($data);
            
            return back()->with('success', 'Catatan servis berhasil disimpan!');
            
        } catch (\Exception $e) {
            Log::error('Gagal simpan maintenance: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // Method updateStatus dan destroy tetap sama seperti sebelumnya
    public function updateStatus(Request $request, $id) {
        $record = Pemeliharaan::findOrFail($id);
        $record->update(['status' => $request->status]);
        return back()->with('success', 'Status diperbarui.');
    }

    public function destroy($id) {
        Pemeliharaan::findOrFail($id)->delete();
        return back()->with('success', 'Data dihapus.');
    }
}