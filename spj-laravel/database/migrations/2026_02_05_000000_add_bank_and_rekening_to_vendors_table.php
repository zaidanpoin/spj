<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'bank')) {
                $table->string('bank')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('vendors', 'rekening')) {
                $table->string('rekening')->nullable()->after('bank');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'rekening')) {
                $table->dropColumn('rekening');
            }
            if (Schema::hasColumn('vendors', 'bank')) {
                $table->dropColumn('bank');
            }
        });
    }
};
