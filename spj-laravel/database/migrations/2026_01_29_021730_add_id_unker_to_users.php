<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // If the column doesn't exist, create it with a foreign key.
        if (!Schema::hasColumn('users', 'id_unker')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('id_unker')
                    ->nullable()
                    ->constrained('unit_kerjas')
                    ->nullOnDelete();
            });
            return;
        }

        // Column exists — ensure the foreign key constraint exists. MySQL constraint
        // name follows the convention `users_id_unker_foreign`.
        $dbName = DB::getDatabaseName();
        $constraint = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?',
            [$dbName, 'users', 'users_id_unker_foreign']
        );

        if (empty($constraint)) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('id_unker')
                    ->references('id')
                    ->on('unit_kerjas')
                    ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('users', 'id_unker')) {
            return;
        }

        $dbName = DB::getDatabaseName();
        $constraint = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?',
            [$dbName, 'users', 'users_id_unker_foreign']
        );

        Schema::table('users', function (Blueprint $table) use ($constraint) {
            if (!empty($constraint)) {
                $table->dropForeign(['id_unker']);
            }

            if (Schema::hasColumn('users', 'id_unker')) {
                $table->dropColumn('id_unker');
            }
        });
    }
};
