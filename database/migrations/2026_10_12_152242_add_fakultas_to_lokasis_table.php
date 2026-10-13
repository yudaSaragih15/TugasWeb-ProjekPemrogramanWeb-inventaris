<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('lokasis', function (Blueprint $table) {
            // Sesuaikan dengan enum fakultas yang sudah kita buat di users
            $table->enum('fakultas', ['FIP', 'FBS', 'FIS', 'FMIPA', 'FT', 'FIK', 'FE'])
                  ->nullable()
                  ->after('gedung'); 
        });
    }

    public function down(): void {
        Schema::table('lokasis', function (Blueprint $table) {
            $table->dropColumn('fakultas');
        });
    }
};