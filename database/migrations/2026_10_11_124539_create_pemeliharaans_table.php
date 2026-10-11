<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pemeliharaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade'); // Relasi ke tabel barang
            $table->date('tanggal_servis');
            $table->enum('jenis_layanan', ['Perbaikan', 'Penggantian Sparepart', 'Pembersihan', 'Kalibrasi']);
            $table->text('deskripsi_kerusakan');
            $table->text('tindakan_perbaikan')->nullable();
            $table->decimal('biaya', 12, 2)->default(0);
            $table->enum('status', ['Selesai', 'Dalam Proses', 'Menunggu Sparepart'])->default('Dalam Proses');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pemeliharaans'); }
};