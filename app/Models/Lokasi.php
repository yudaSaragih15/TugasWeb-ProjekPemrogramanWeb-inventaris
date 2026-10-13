<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama_lokasi',
        'gedung',
        'fakultas', // <--- WAJIB ADA AGAR BISA DISIMPAN VIA CONTROLLER
    ];

    /**
     * Relasi: Satu lokasi bisa memiliki banyak barang
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'lokasi', 'nama_lokasi');
    }
}