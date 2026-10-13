<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'pemeliharaans';

    /**
     * Atribut yang boleh diisi secara massal
     * PENTING: 'fakultas' wajib ada untuk filtering per fakultas
     */
    protected $fillable = [
        'barang_id',
        'tanggal_servis',
        'jenis_layanan',
        'deskripsi_kerusakan',
        'tindakan_perbaikan',
        'biaya',
        'status',
        'fakultas', 
    ];

    /**
     * Relasi ke tabel Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}