<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar aset (Data Aset)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Barang::query();

        // Filter Fakultas (Safe Check: hanya filter jika kolom ada)
        if (Schema::hasColumn('barang', 'fakultas')) {
            $query->where('fakultas', $user->fakultas);
        }

        // Logika Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // Logika Filter Dropdown
        if ($request->filled('kondisi')) $query->where('kondisi', $request->kondisi);
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);

        $barangs = $query->latest()->paginate(10)->withQueryString();
        
        // Ambil data unik untuk opsi filter
        $filterQuery = Barang::query();
        if (Schema::hasColumn('barang', 'fakultas')) $filterQuery->where('fakultas', $user->fakultas);
        $kategoris = $filterQuery->distinct()->pluck('kategori');
        $kondisis = ['Baik', 'Rusak Ringan', 'Rusak Berat'];

        return view('barang.index', compact('barangs', 'kategoris', 'kondisis'));
    }

    /**
     * Menampilkan form tambah barang
     */
    public function create()
    {
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('barang.create', compact('lokasis', 'kategoris'));
    }

    /**
     * Menyimpan barang baru + UPLOAD FOTO OTOMATIS
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
        
        // Isi fakultas otomatis jika kolom tersedia
        if (Schema::hasColumn('barang', 'fakultas')) {
            $data['fakultas'] = auth()->user()->fakultas;
        }

        // PROSES UPLOAD FOTO
        if ($request->hasFile('foto')) {
            // Simpan file ke folder storage/app/public/fotos-barang
            // Path yang disimpan ke DB akan seperti: fotos-barang/namafile.jpg
            $path = $request->file('foto')->store('fotos-barang', 'public');
            $data['foto'] = $path;
        }

        Barang::create($data);
        return redirect()->route('barang.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit barang
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('barang.edit', compact('barang', 'lokasis', 'kategoris'));
    }

    /**
     * Update data barang + Ganti foto jika ada upload baru
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        
        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang,' . $id,
            'nama_barang' => 'required', 'kategori' => 'required', 'lokasi' => 'required',
            'jumlah' => 'required|numeric|min:1', 'kondisi' => 'required',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->except('foto');
        
        // Jika ada upload foto baru, hapus yang lama dan simpan yang baru
        if ($request->hasFile('foto')) {
            if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
                Storage::disk('public')->delete($barang->foto);
            }
            $data['foto'] = $request->file('foto')->store('fotos-barang', 'public');
        }

        $barang->update($data);
        return redirect()->route('barang.index')->with('success', 'Data aset berhasil diperbarui.');
    }

    /**
     * Hapus sementara (Soft Delete / Arsip)
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete(); 
        return redirect()->route('barang.index')->with('success', 'Aset dipindahkan ke arsip.');
    }

    /**
     * Menampilkan data yang sudah diarsip (Trashed)
     */
    public function trashed()
    {
        $user = auth()->user();
        $query = Barang::onlyTrashed();
        
        if (Schema::hasColumn('barang', 'fakultas')) {
            $query->where('fakultas', $user->fakultas);
        }

        $barangs = $query->latest()->paginate(10);
        return view('barang.trashed', compact('barangs'));
    }

    /**
     * Mengembalikan data dari arsip (Restore)
     */
    public function restore($id)
    {
        $barang = Barang::withTrashed()->findOrFail($id);
        
        // Cek keamanan fakultas
        if (Schema::hasColumn('barang', 'fakultas') && auth()->user()->fakultas !== $barang->fakultas && auth()->user()->role !== 'super_admin') {
             abort(403);
        }

        $barang->restore();
        return redirect()->route('barang.trashed')->with('success', 'Aset berhasil dikembalikan.');
    }

    /**
     * Hapus Permanen (Force Delete) - Untuk memperbaiki error route di arsip
     */
    public function forceDelete($id)
    {
        $barang = Barang::withTrashed()->findOrFail($id);
        
        // Hapus file foto fisik dari server agar tidak menumpuk
        if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
            Storage::disk('public')->delete($barang->foto);
        }
        
        $barang->forceDelete();
        return back()->with('success', 'Aset dihapus permanen dari sistem.');
    }

    /**
     * Export Excel (Placeholder)
     */
    public function exportExcel()
    {
        return back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}