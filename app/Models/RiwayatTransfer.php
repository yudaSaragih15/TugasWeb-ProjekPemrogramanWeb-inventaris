<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTransfer extends Model
{
    use HasFactory;

    protected $table = 'riwayat_transfer';

    protected $fillable = [
        'barang_id',
        'lokasi_asal',
        'lokasi_tujuan',
        'jumlah_ditransfer',
        'keterangan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}