<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dieta_registro_dieta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_dieta_id')->constrained('registro_dietas')->onDelete('cascade');
            $table->foreignId('dieta_id')->constrained('dietas')->onDelete('cascade');
            $table->timestamps();
        });
        // Eliminar columna dieta_id de registro_dietas si existe
        if (Schema::hasColumn('registro_dietas', 'dieta_id')) {
            Schema::table('registro_dietas', function (Blueprint $table) {
                $table->dropForeign(['dieta_id']);
                $table->dropColumn('dieta_id');
            });
        }
    }
    public function down()
    {
        Schema::dropIfExists('dieta_registro_dieta');
        // (Opcional) volver a agregar dieta_id a registro_dietas si se revierte
    }
};
