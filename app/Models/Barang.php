<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'lokasi',
        'jumlah',
        'kondisi',
        'foto',
        'fakultas', // <--- PENTING: Agar bisa diisi otomatis oleh controller
    ];

    protected $dates = ['deleted_at'];
}