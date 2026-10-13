<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('barang', function (Blueprint $table) {
            // Menambahkan kolom fakultas setelah kolom 'kondisi'
            $table->string('fakultas')->nullable()->after('kondisi');
        });
    }

    public function down(): void {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('fakultas');
        });
    }
};