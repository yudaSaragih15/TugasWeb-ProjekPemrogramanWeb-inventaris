<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi')->unique(); // Contoh: Lab Komputer 1, Ruang Dosen A
            $table->string('gedung')->nullable();    // Opsional: Gedung A, Gedung B
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('lokasis'); }
};