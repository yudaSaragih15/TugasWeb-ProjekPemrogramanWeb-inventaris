<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model {
    protected $fillable = [
        'barang_id', 'tanggal_servis', 'jenis_layanan', 
        'deskripsi_kerusakan', 'tindakan_perbaikan', 'biaya', 'status'
    ];

    // Relasi: Satu pemeliharaan milik satu barang
    public function barang() {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}