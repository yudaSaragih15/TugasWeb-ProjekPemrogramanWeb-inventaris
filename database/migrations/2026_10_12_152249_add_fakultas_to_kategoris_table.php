<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->enum('fakultas', ['FIP', 'FBS', 'FIS', 'FMIPA', 'FT', 'FIK', 'FE'])
                  ->nullable()
                  ->after('deskripsi');
        });
    }

    public function down(): void {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropColumn('fakultas');
        });
    }
};