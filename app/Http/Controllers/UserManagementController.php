<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller {
    public function index() {
        $user = auth()->user();
        
        // Super Admin lihat semua, Admin Fakultas lihat fakultasnya saja (fallback)
        if ($user->role === 'super_admin') {
            $users = User::latest()->paginate(10);
        } else {
            $users = User::where('fakultas', $user->fakultas)->latest()->paginate(10);
        }
        
        $fakultasList = ['FIP', 'FBS', 'FIS', 'FMIPA', 'FT', 'FIK', 'FE'];
        return view('users.index', compact('users', 'fakultasList'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'fakultas' => 'required|in:FIP,FBS,FIS,FMIPA,FT,FIK,FE',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'fakultas' => $request->fakultas,
            'password' => Hash::make($request->password),
            'role' => 'admin_fakultas', // Default role saat dibuat super admin
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        // Cegah super admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function resetPassword(Request $request, $id) {
        $request->validate(['new_password' => 'required|min:8']);
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Password user berhasil direset.');
    }
}