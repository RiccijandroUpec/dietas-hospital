<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('registro_dietas') && !Schema::hasColumn('registro_dietas', 'fecha')) {
            Schema::table('registro_dietas', function (Blueprint $table) {
                $table->date('fecha')->nullable();
            });

            // Backfill fecha from created_at if available
            DB::table('registro_dietas')
                ->whereNull('fecha')
                ->update(['fecha' => DB::raw('DATE(`created_at`)')]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('registro_dietas') && Schema::hasColumn('registro_dietas', 'fecha')) {
            Schema::table('registro_dietas', function (Blueprint $table) {
                $table->dropColumn('fecha');
            });
        }
    }
};
