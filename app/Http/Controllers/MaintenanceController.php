<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        // Nanti di sini logic untuk menampilkan riwayat servis/perbaikan
        return view('maintenance.index');
    }
}