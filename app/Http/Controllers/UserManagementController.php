<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserManagementController extends Controller
{
    public function index()
    {
        // Mengambil semua user kecuali yang sedang login (opsional)
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }
}