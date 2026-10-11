<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        // Nanti di sini logic untuk menampilkan Lokasi & Kategori
        return view('master-data.index');
    }
}