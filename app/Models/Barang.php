<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database MySQL.
     * Sesuai migration Yuda: Schema::create('barang', ...)
     */
    protected $table = 'barang';

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'jumlah',
        'kondisi',
        'lokasi',
        'foto',
    ];

    /**
     * Type casting atribut.
     */
    protected $casts = [
        'jumlah' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi One-to-Many ke RiwayatTransfer.
     */
    public function riwayatTransfers(): HasMany
    {
        return $this->hasMany(RiwayatTransfer::class, 'barang_id')->latest('id');
    }
}