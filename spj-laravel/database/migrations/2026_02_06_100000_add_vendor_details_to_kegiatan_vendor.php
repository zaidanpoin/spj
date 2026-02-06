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
        Schema::table('kegiatan_vendor', function (Blueprint $table) {
            // Data detail vendor yang bisa berbeda per kegiatan
            // Setiap kegiatan bisa punya data vendor yang berbeda untuk vendor yang sama
            $table->string('nama_direktur')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('npwp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('bank')->nullable();
            $table->string('rekening')->nullable();
            $table->string('ppn')->nullable()->comment('Nilai PPN: 0, 11, atau 12');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_vendor', function (Blueprint $table) {
            $table->dropColumn([
                'nama_direktur',
                'jabatan',
                'npwp',
                'alamat',
                'bank',
                'rekening',
                'ppn'
            ]);
        });
    }
};
