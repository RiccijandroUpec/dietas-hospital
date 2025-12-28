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
        if (Schema::hasTable('registro_dietas') && !Schema::hasColumn('registro_dietas', 'es_tardia')) {
            // Posicionar la columna después de 'observaciones' solo si existe; caso contrario la agrega al final
            $afterColumn = Schema::hasColumn('registro_dietas', 'observaciones') ? 'observaciones' : null;

            Schema::table('registro_dietas', function (Blueprint $table) use ($afterColumn) {
                if ($afterColumn) {
                    $table->boolean('es_tardia')->default(false)->after($afterColumn);
                } else {
                    $table->boolean('es_tardia')->default(false);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('registro_dietas') && Schema::hasColumn('registro_dietas', 'es_tardia')) {
            Schema::table('registro_dietas', function (Blueprint $table) {
                $table->dropColumn('es_tardia');
            });
        }
    }
};
