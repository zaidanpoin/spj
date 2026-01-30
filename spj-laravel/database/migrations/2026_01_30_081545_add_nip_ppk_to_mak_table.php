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
        Schema::table('mak', function (Blueprint $table) {
            $table->string('nip_ppk', 20)->nullable()->after('paket');
            $table->index('nip_ppk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mak', function (Blueprint $table) {
            $table->dropIndex(['nip_ppk']);
            $table->dropColumn('nip_ppk');
        });
    }
};
