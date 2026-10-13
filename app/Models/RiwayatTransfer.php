<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTransfer extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database (sesuaikan jika berbeda)
     */
    protected $table = 'riwayat_transfers';

    /**
     * Atribut yang boleh diisi secara massal
     * PENTING: 'fakultas' harus ada di sini agar bisa disimpan oleh controller
     */
    protected $fillable = [
        'barang_id',
        'lokasi_asal',
        'lokasi_tujuan',
        'jumlah',
        'keterangan',
        'tanggal',
        'fakultas', 
        'user_id',
    ];

    /**
     * Relasi ke tabel Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi ke User (Admin yang melakukan transfer)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}