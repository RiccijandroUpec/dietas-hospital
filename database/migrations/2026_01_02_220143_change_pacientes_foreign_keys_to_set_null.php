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
        Schema::table('pacientes', function (Blueprint $table) {
            // Eliminar las restricciones existentes
            $table->dropForeign(['servicio_id']);
            $table->dropForeign(['cama_id']);
        });
        
        Schema::table('pacientes', function (Blueprint $table) {
            // Hacer las columnas nullable
            $table->foreignId('servicio_id')->nullable()->change();
            $table->foreignId('cama_id')->nullable()->change();
            
            // Recrear con SET NULL en lugar de CASCADE
            $table->foreign('servicio_id')
                ->references('id')
                ->on('servicios')
                ->onDelete('set null');
                
            $table->foreign('cama_id')
                ->references('id')
                ->on('camas')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // Eliminar restricciones actuales
            $table->dropForeign(['servicio_id']);
            $table->dropForeign(['cama_id']);
        });
        
        Schema::table('pacientes', function (Blueprint $table) {
            // Volver a NOT NULL
            $table->foreignId('servicio_id')->nullable(false)->change();
            $table->foreignId('cama_id')->nullable(false)->change();
            
            // Revertir a CASCADE
            $table->foreign('servicio_id')
                ->references('id')
                ->on('servicios')
                ->onDelete('cascade');
                
            $table->foreign('cama_id')
                ->references('id')
                ->on('camas')
                ->onDelete('cascade');
        });
    }
};
