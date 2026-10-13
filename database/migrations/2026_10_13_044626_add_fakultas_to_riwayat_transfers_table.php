Schema::table('riwayat_transfers', function (Blueprint $table) {
    $table->string('fakultas')->nullable()->after('keterangan'); 
    // Sesuaikan 'after' dengan kolom terakhir di tabelmu
});