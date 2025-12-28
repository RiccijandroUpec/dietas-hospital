<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('registro_dietas') && !Schema::hasColumn('registro_dietas', 'observaciones')) {
            Schema::table('registro_dietas', function (Blueprint $table) {
                $table->text('observaciones')->nullable()->after('fecha');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('registro_dietas') && Schema::hasColumn('registro_dietas', 'observaciones')) {
            Schema::table('registro_dietas', function (Blueprint $table) {
                $table->dropColumn('observaciones');
            });
        }
    }
};
