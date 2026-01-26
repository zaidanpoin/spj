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
        Schema::table('konsumsis', function (Blueprint $table) {
            $table->enum('kategori', ['snack', 'makanan', 'barang'])->default('snack')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konsumsis', function (Blueprint $table) {
            $table->enum('kategori', ['snack', 'makanan'])->default('snack')->change();
        });
    }
};
