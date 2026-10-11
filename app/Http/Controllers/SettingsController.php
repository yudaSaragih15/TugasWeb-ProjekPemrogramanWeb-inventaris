<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    /**
     * Menampilkan Halaman Pengaturan
     */
    public function index()
    {
        // Membaca konfigurasi dari file JSON settings.json di storage
        // Ini cara paling aman dan fleksibel tanpa perlu tabel database baru
        $settingsPath = storage_path('app/settings.json');
        
        if (File::exists($settingsPath)) {
            $settings = json_decode(File::get($settingsPath), true);
        } else {
            // Default values jika file belum ada
            $settings = [
                'app_name' => 'Inventaris Kampus',
                'institution_logo' => null,
                'low_stock_threshold' => 5,
                'auto_code_format' => 'BRG-{YYYY}-{####}',
            ];
        }

        return view('settings.index', compact('settings'));
    }

    /**
     * Menyimpan Perubahan Pengaturan
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:100',
            'institution_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            'low_stock_threshold' => 'required|integer|min:1|max:100',
            'auto_code_format' => 'required|string|max:50',
        ]);

        // Baca settings lama terlebih dahulu
        $settingsPath = storage_path('app/settings.json');
        $settings = File::exists($settingsPath) 
            ? json_decode(File::get($settingsPath), true) 
            : [];

        // Update teks settings
        $settings['app_name'] = $request->app_name;
        $settings['low_stock_threshold'] = (int) $request->low_stock_threshold;
        $settings['auto_code_format'] = $request->auto_code_format;

        // Handle Upload Logo Baru
        if ($request->hasFile('institution_logo')) {
            // Hapus logo lama jika ada
            if (!empty($settings['institution_logo']) && Storage::disk('public')->exists($settings['institution_logo'])) {
                Storage::disk('public')->delete($settings['institution_logo']);
            }
            
            // Simpan logo baru
            $path = $request->file('institution_logo')->store('logos', 'public');
            $settings['institution_logo'] = $path;
        }

        // Simpan kembali ke file JSON
        File::put($settingsPath, json_encode($settings, JSON_PRETTY_PRINT));

        return redirect()->route('settings.index')->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }
}