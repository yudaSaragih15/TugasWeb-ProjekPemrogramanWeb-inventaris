<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index(Request $request)
    {
        // Default aktif di tab Lokasi
        $activeTab = $request->get('tab', 'lokasi');
        
        $lokasis = Lokasi::latest()->get();
        $kategoris = Kategori::latest()->get();

        return view('master-data.index', compact('activeTab', 'lokasis', 'kategoris'));
    }

    public function storeLokasi(Request $request)
    {
        $request->validate(['nama_lokasi' => 'required|unique:lokasis', 'gedung' => 'nullable']);
        Lokasi::create($request->all());
        return redirect()->route('master-data.index', ['tab' => 'lokasi'])->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function storeKategori(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|unique:kategoris']);
        Kategori::create($request->all());
        return redirect()->route('master-data.index', ['tab' => 'kategori'])->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function destroyLokasi($id) {
        Lokasi::findOrFail($id)->delete();
        return back()->with('success', 'Lokasi dihapus.');
    }

    public function destroyKategori($id) {
        Kategori::findOrFail($id)->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}