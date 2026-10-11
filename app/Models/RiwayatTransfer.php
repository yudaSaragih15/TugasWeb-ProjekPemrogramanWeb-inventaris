<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatTransfer extends Model
{
    use HasFactory;

    /**
     * Nama tabel riwayat mutasi di database MySQL.
     */
    protected $table = 'riwayat_transfers';

    /**
     * Kolom fillable sesuai migration Yuda:
     * barang_id, lokasi_asal, lokasi_tujuan, jumlah, keterangan, tanggal.
     */
    protected $fillable = [
        'barang_id',
        'lokasi_asal',
        'lokasi_tujuan',
        'jumlah',
        'keterangan',
        'tanggal',
    ];

    /**
     * Casting tipe data kolom.
     */
    protected $casts = [
        'jumlah' => 'integer',
        'tanggal' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accessor Defensif:
     * Mengizinkan pemanggilan $item->tanggal_transfer maupun $item->tanggal
     * di view tanpa memicu error Undefined Property.
     */
    public function getTanggalTransferAttribute()
    {
        return $this->attributes['tanggal'] 
            ?? $this->attributes['tanggal_transfer'] 
            ?? $this->created_at;
    }

    /**
     * Relasi Many-to-One ke model Barang.
     * ->withTrashed() disematkan agar catatan audit trail tetap menampilkan
     * nama aset fisik meskipun barang master sempat diarsipkan.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id')->withTrashed();
    }
}