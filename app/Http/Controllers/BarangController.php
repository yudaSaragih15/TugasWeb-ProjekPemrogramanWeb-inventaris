<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatTransfer;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Halaman Data Aset lengkap
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
        }

        $barangs = $query->latest()->paginate(10);
        return view('barang.index', compact('barangs'));
    }

    // Simpan Aset Baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang',
            'nama_barang' => 'required',
            'kategori'    => 'required',
            'lokasi'      => 'required',
            'jumlah'      => 'required|numeric|min:1',
            'kondisi'     => 'required',
        ]);

        Barang::create($request->all());

        return redirect()->back()->with('success', 'Aset baru berhasil ditambahkan!');
    }

    // Update Data Aset
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        
        $request->validate([
            'nama_barang' => 'required',
            'kategori'    => 'required',
            'lokasi'      => 'required',
            'jumlah'      => 'required|numeric|min:0',
            'kondisi'     => 'required',
        ]);

        $barang->update($request->all());

        return redirect()->back()->with('success', 'Data aset berhasil diperbarui!');
    }

    // Hapus Aset
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->back()->with('success', 'Aset berhasil dihapus!');
    }

    // Proses Transfer Aset ke Lokasi Lain
    public function transfer(Request $request, $id)
    {
        $request->validate([
            'lokasi_tujuan'    => 'required',
            'jumlah_ditransfer'=> 'required|numeric|min:1',
            'keterangan'       => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($id);

        if ($request->jumlah_ditransfer > $barang->jumlah) {
            return redirect()->back()->with('error', 'Jumlah transfer melebihi stok yang ada!');
        }

        $lokasi_asal = $barang->lokasi;

        // Catat Riwayat Transfer
        RiwayatTransfer::create([
            'barang_id'         => $barang->id,
            'lokasi_asal'       => $lokasi_asal,
            'lokasi_tujuan'     => $request->lokasi_tujuan,
            'jumlah_ditransfer' => $request->jumlah_ditransfer,
            'keterangan'        => $request->keterangan ?? 'Pemindahan lokasi rutin',
        ]);

        // Update Lokasi Aset
        $barang->lokasi = $request->lokasi_tujuan;
        $barang->save();

        return redirect()->back()->with('success', 'Aset berhasil ditransfer ke ' . $request->lokasi_tujuan);
    }

    // Halaman Riwayat Transfer Aset
    public function riwayatTransfer()
    {
        $riwayats = RiwayatTransfer::with('barang')->latest()->paginate(10);
        return view('barang.transfer', compact('riwayats'));
    }
}