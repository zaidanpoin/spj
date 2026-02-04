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
        Schema::create('sppd', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatans')->onDelete('cascade');

            // Basic SPPD Information
            $table->string('lembar', 50)->default('');
            $table->string('kode_no', 50)->default('');
            $table->string('no', 50)->default('');
            $table->string('nospd', 50)->default('');
            $table->date('tgl')->nullable();
            $table->date('tglspd')->nullable();

            // Pelaksana Perjalanan Dinas
            $table->string('nama', 70)->default('');
            $table->string('nip', 30)->default('');
            $table->string('pangkat_gol', 100)->default('');
            $table->string('jabatan', 500)->default('');

            // Detail Perjalanan
            $table->string('maksud', 255)->default('');
            $table->string('kendaraan', 100)->default('');
            $table->string('tujuan', 255)->default('');
            $table->date('tgl_brkt')->nullable(); // Tanggal Berangkat
            $table->date('tgl_kbl')->nullable();  // Tanggal Kembali
            $table->string('catatan', 255)->default('');

            // Pejabat Pemberi Perintah
            $table->string('perintah_nama', 50)->default('');
            $table->string('perintah_nip', 20)->default('');
            $table->string('perintah_jabatan', 800)->default('');

            // PPK (Pejabat Pembuat Komitmen)
            $table->string('ppk_nama', 100)->default('');
            $table->string('ppk_nip', 30)->default('');
            $table->string('kdppk', 2)->nullable();

            // Bendahara
            $table->string('bendahara_nama', 70)->default('');
            $table->string('bendahara_nip', 30)->default('');

            // Pembuat SPPD
            $table->string('pembuat_nama', 70)->default('');
            $table->string('pembuat_nip', 30)->default('');

            // Detail Biaya
            $table->string('tingkat_biaya', 100)->default('');
            $table->string('alat_angkut', 255)->default('');
            $table->string('tempat_berangkat', 100)->default('');
            $table->string('akun', 20)->default('');
            $table->date('tgl_kwit')->nullable();
            $table->string('kembali_baru', 100)->default('');

            // Jenis dan Status
            $table->tinyInteger('jenis_tujuan')->unsigned()->default(0);
            $table->tinyInteger('jenis_board')->unsigned()->default(0);
            $table->date('tgl_selesai')->nullable();
            $table->integer('grup')->default(0);
            $table->string('eselon', 20)->default('');
            $table->bigInteger('nominatif_id')->nullable();

            // Tahap I - Berangkat dari tempat kedudukan
            $table->string('I_dari', 50)->nullable();
            $table->string('I_ke', 50)->nullable();
            $table->date('I_tgl')->nullable();
            $table->string('I_instansi_nama', 300)->nullable();
            $table->string('I_nama', 50)->nullable();
            $table->string('I_nip', 18)->nullable();

            // Tahap II - Tiba di
            $table->string('II_tiba', 50)->nullable();
            $table->date('II_tiba_tgl')->nullable();
            $table->string('II_tiba_instansi_nama', 300)->nullable();
            $table->string('II_tiba_nama', 50)->nullable();
            $table->string('II_tiba_nip', 18)->nullable();

            // Tahap II - Berangkat dari
            $table->string('II_dari', 50)->nullable();
            $table->string('II_ke', 50)->nullable();
            $table->date('II_tgl')->nullable();
            $table->string('II_instansi_nama', 300)->nullable();
            $table->string('II_nama', 50)->nullable();
            $table->string('II_nip', 18)->nullable();

            // Tahap III - Tiba di
            $table->string('III_tiba', 50)->nullable();
            $table->date('III_tiba_tgl')->nullable();
            $table->string('III_tiba_instansi_nama', 300)->nullable();
            $table->string('III_tiba_nama', 50)->nullable();
            $table->string('III_tiba_nip', 18)->nullable();

            // Tahap III - Berangkat dari
            $table->string('III_dari', 50)->nullable();
            $table->string('III_ke', 50)->nullable();
            $table->date('III_tgl')->nullable();
            $table->string('III_instansi_nama', 300)->nullable();
            $table->string('III_nama', 50)->nullable();
            $table->string('III_nip', 18)->nullable();

            // Tahap IV - Tiba di
            $table->string('IV_tiba', 50)->nullable();
            $table->date('IV_tiba_tgl')->nullable();
            $table->string('IV_tiba_instansi_nama', 300)->nullable();
            $table->string('IV_tiba_nama', 50)->nullable();
            $table->string('IV_tiba_nip', 18)->nullable();

            // Tahap IV - Berangkat dari
            $table->string('IV_dari', 50)->nullable();
            $table->string('IV_ke', 50)->nullable();
            $table->date('IV_tgl')->nullable();
            $table->string('IV_instansi_nama', 300)->nullable();
            $table->string('IV_nama', 50)->nullable();
            $table->string('IV_nip', 18)->nullable();

            // Tahap V - Tiba di
            $table->string('V_tiba', 50)->nullable();
            $table->date('V_tiba_tgl')->nullable();
            $table->string('V_tiba_instansi_nama', 300)->nullable();
            $table->string('V_tiba_nama', 50)->nullable();
            $table->string('V_tiba_nip', 18)->nullable();

            // Tahap V - Berangkat dari
            $table->string('V_dari', 50)->nullable();
            $table->string('V_ke', 50)->nullable();
            $table->date('V_tgl')->nullable();
            $table->string('V_instansi_nama', 300)->nullable();
            $table->string('V_nama', 50)->nullable();
            $table->string('V_nip', 18)->nullable();

            // Tahap VI - Tiba di
            $table->string('VI_tiba', 50)->nullable();
            $table->date('VI_tiba_tgl')->nullable();
            $table->string('VI_tiba_instansi_nama', 300)->nullable();
            $table->string('VI_tiba_nama', 50)->nullable();
            $table->string('VI_tiba_nip', 18)->nullable();

            // Additional Info
            $table->date('update_date')->nullable();
            $table->integer('update_user')->default(0);
            $table->string('panjar', 100)->nullable();
            $table->string('kdmak', 300)->nullable();
            $table->string('mak', 255)->nullable();
            $table->tinyInteger('is_pns')->default(0);
            $table->enum('status', ['draft', 'final'])->default('draft');

            $table->timestamps();

            // Indexes
            $table->index('tgl');
            $table->index('grup');
            $table->index('tgl_brkt');
            $table->index('tgl_kbl');
            $table->index('is_pns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sppd');
    }
};
