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
        Schema::create('kegiatan_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');

            // Nomor-nomor surat yang bisa berbeda per kombinasi kegiatan-vendor
            $table->string('nomor_berita_acara')->nullable();
            $table->string('nomor_bast')->nullable()->comment('Nomor Berita Acara Serah Terima Barang/Pekerjaan');
            $table->string('nomor_berita_pembayaran')->nullable();

            $table->timestamps();

            // Pastikan kombinasi kegiatan_id dan vendor_id unik
            $table->unique(['kegiatan_id', 'vendor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_vendor');
    }
};
