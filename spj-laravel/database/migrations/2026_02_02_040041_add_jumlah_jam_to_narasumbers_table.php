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
        Schema::table('narasumbers', function (Blueprint $table) {
            $table->integer('jumlah_jam')->default(1)->after('npwp'); // Jumlah OJ (Orang Jam)
            $table->integer('tarif_per_jam')->nullable()->after('jumlah_jam'); // Tarif per jam (opsional, bisa dari SBM)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('narasumbers', function (Blueprint $table) {
            $table->dropColumn(['jumlah_jam', 'tarif_per_jam']);
        });
    }
};
