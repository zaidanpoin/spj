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
        Schema::create('sbm', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 50)->default('');
            $table->string('kelas', 50)->default('');
            $table->string('item', 100)->default('');
            $table->string('satuan_sing', 100)->default('');
            $table->string('satuan_desk', 100)->default('');
            $table->string('nilai', 10)->default('');
            $table->date('update_date')->nullable();
            $table->integer('update_user')->nullable();
            $table->year('thang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sbm_table_sppd');
    }
};
