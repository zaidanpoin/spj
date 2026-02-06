<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameNomorBuktiBahanToKonsumsiInKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new column, copy data from old column, then drop old column
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->string('nomor_bukti_konsumsi')->nullable()->after('file_laporan');
        });

        // Copy data from old column to new column if exists
        if (Schema::hasColumn('kegiatans', 'nomor_bukti_bahan')) {
            DB::table('kegiatans')
                ->whereNotNull('nomor_bukti_bahan')
                ->update(['nomor_bukti_konsumsi' => DB::raw('nomor_bukti_bahan')]);

            Schema::table('kegiatans', function (Blueprint $table) {
                $table->dropColumn('nomor_bukti_bahan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->string('nomor_bukti_bahan')->nullable()->after('file_laporan');
        });

        if (Schema::hasColumn('kegiatans', 'nomor_bukti_konsumsi')) {
            DB::table('kegiatans')
                ->whereNotNull('nomor_bukti_konsumsi')
                ->update(['nomor_bukti_bahan' => DB::raw('nomor_bukti_konsumsi')]);

            Schema::table('kegiatans', function (Blueprint $table) {
                $table->dropColumn('nomor_bukti_konsumsi');
            });
        }
    }
}
