<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTransfer extends Model
{
    use HasFactory;

    protected $table = 'riwayat_transfers';

    protected $fillable = [
        'barang_id',
        'lokasi_asal',
        'lokasi_tujuan',
        'jumlah',
        'keterangan',
        'tanggal',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}