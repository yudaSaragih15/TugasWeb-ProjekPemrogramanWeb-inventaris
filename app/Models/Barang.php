<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- WAJIB ADA

class Barang extends Model
{
    use HasFactory, SoftDeletes; // <--- TAMBAHKAN SoftDeletes DI SINI

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'lokasi',
        'jumlah',
        'kondisi',
        'foto', // <--- TAMBAHKAN INI AGAR BISA DISIMPAN VIA MASS ASSIGNMENT
    ];

    // Opsional: Format tanggal deleted_at agar rapi
    protected $dates = ['deleted_at'];
}