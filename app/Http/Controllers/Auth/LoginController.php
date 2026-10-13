<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * TAMPILKAN HALAMAN LOGIN (MURNI VIEW, TANPA REDIRECT PAKSA)
     */
    public function showLoginForm() {
        // Kita biarkan saja. Jika user sudah login nekat akses /login, 
        // biarkan mereka melihat form login lagi. Ini mencegah loop redirect.
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * LOGOUT YANG MENGHANCURKAN SESSION SECARA TOTAL
     */
    public function logout(Request $request) {
        Auth::logout();
        
        // Hapus semua data session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Return JSON jika dipanggil via AJAX (untuk tombol JS kita nanti)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => 'logged_out']);
        }

        // Redirect standar jika dipanggil via form biasa
        return redirect()->route('login');
    }

    // Backup register (tidak dipakai UI, tapi aman dibiarkan)
    public function showRegisterForm() {
        $fakultasList = ['FIP', 'FBS', 'FIS', 'FMIPA', 'FT', 'FIK', 'FE'];
        return view('auth.register', compact('fakultasList'));
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'fakultas' => 'required|in:FIP,FBS,FIS,FMIPA,FT,FIK,FE',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'fakultas' => $request->fakultas,
            'role' => 'admin_fakultas',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat!');
    }
}