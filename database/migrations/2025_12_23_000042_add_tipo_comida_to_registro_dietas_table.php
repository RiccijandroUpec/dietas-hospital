<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('registro_dietas') && !Schema::hasColumn('registro_dietas', 'tipo_comida')) {
            Schema::table('registro_dietas', function (Blueprint $table) {
                $table->enum('tipo_comida', ['desayuno', 'almuerzo', 'merienda'])->default('desayuno')->after('dieta_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('registro_dietas') && Schema::hasColumn('registro_dietas', 'tipo_comida')) {
            Schema::table('registro_dietas', function (Blueprint $table) {
                $table->dropColumn('tipo_comida');
            });
        }
    }
};
