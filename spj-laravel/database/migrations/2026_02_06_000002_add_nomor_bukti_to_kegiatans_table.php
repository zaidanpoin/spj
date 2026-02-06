<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->string('nomor_bukti_bahan')->nullable()->after('file_laporan');
            $table->string('nomor_bukti_profesi')->nullable()->after('nomor_bukti_bahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn(['nomor_bukti_bahan', 'nomor_bukti_profesi']);
        });
    }
};
