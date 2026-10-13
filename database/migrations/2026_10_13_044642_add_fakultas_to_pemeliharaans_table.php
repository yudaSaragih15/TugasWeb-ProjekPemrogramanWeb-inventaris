Schema::table('pemeliharaans', function (Blueprint $table) {
    $table->string('fakultas')->nullable()->after('status');
});