<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Tampilan Ringkasan Dashboard (TIDAK DIUBAH AGAR AMAN)
     */
    public function dashboard()
    {
        $totalBarang = Barang::count();
        $totalJumlah = Barang::sum('jumlah') ?? 0;
        $barangTerbaru = Barang::latest()->take(5)->get();

        return view('dashboard', compact('totalBarang', 'totalJumlah', 'barangTerbaru'));
    }

    /**
     * FITUR 1: PENCARIAN & FILTER LANJUTAN + INDEX
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        // Logika Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // Logika Filter Dropdown
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $barangs = $query->latest()->paginate(10)->withQueryString();
        
        // Mengambil data unik untuk opsi dropdown filter
        $kategoris = Barang::distinct()->pluck('kategori');
        $kondisis = ['Baik', 'Rusak Ringan', 'Rusak Berat'];

        return view('barang.index', compact('barangs', 'kategoris', 'kondisis'));
    }

    /**
     * FITUR 2: EKSPOR DATA KE EXCEL (CSV)
     */
    public function exportExcel()
    {
        $filename = "data-aset-inventaris-" . date('Y-m-d-His') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Kode Barang', 'Nama Barang', 'Kategori', 'Lokasi', 'Jumlah', 'Kondisi'];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach (Barang::all() as $barang) {
                fputcsv($file, [
                    $barang->kode_barang,
                    $barang->nama_barang,
                    $barang->kategori,
                    $barang->lokasi,
                    $barang->jumlah,
                    $barang->kondisi
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        return view('barang.create');
    }

    /**
     * SIMPAN BARANG BARU + UPLOAD FOTO
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang',
            'nama_barang' => 'required',
            'kategori'    => 'required',
            'lokasi'      => 'required',
            'jumlah'      => 'required|numeric|min:1',
            'kondisi'     => 'required',
            'foto'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Max 2MB
        ]);

        $data = $request->except('foto');

        // Proses Upload Foto jika ada
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fotos-barang', 'public');
            $data['foto'] = $path;
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    /**
     * UPDATE DATA + GANTI FOTO
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang,' . $id,
            'nama_barang' => 'required',
            'kategori'    => 'required',
            'lokasi'      => 'required',
            'jumlah'      => 'required|numeric|min:1',
            'kondisi'     => 'required',
            'foto'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->except('foto');

        // Jika user upload foto baru, hapus yang lama dan simpan yang baru
        if ($request->hasFile('foto')) {
            if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
                Storage::disk('public')->delete($barang->foto);
            }
            $path = $request->file('foto')->store('fotos-barang', 'public');
            $data['foto'] = $path;
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Data aset berhasil diperbarui.');
    }

    /**
     * FITUR 6: SOFT DELETE (HAPUS SEMENTARA)
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete(); // Karena model pakai SoftDeletes, ini hanya set deleted_at

        return redirect()->route('barang.index')->with('success', 'Aset dipindahkan ke arsip.');
    }

    /**
     * FITUR 6: RESTORE DARI ARSIP
     */
    public function restore($id)
    {
        $barang = Barang::withTrashed()->findOrFail($id);
        $barang->restore();
        return redirect()->route('barang.index')->with('success', 'Aset berhasil dikembalikan dari arsip.');
    }

    /**
     * Halaman Transfer & Riwayat Aset (AMAN TIDAK DIUBAH)
     */
    public function riwayatTransfer()
    {
        return view('barang.riwayat_transfer');
    }
}