<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            // Menambahkan kolom foto (nullable agar tidak error saat input tanpa foto)
            $table->string('foto')->nullable()->after('kondisi');
            
            // Mengaktifkan soft deletes (menambah kolom deleted_at)
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('foto');
            $table->dropSoftDeletes();
        });
    }
};