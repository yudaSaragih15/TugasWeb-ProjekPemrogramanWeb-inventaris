<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'fakultas', // <--- WAJIB ADA AGAR BISA DISIMPAN VIA CONTROLLER
    ];

    /**
     * Relasi: Satu kategori bisa memiliki banyak barang
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'kategori', 'nama_kategori');
    }
}