<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('pacientes')) {
            return;
        }

        // Si la columna ya existe, no hacer nada
        if (Schema::hasColumn('pacientes', 'estado')) {
            return;
        }

        // Agregar la columna estado con valor por defecto
        Schema::table('pacientes', function (Blueprint $table) {
            $table->enum('estado', ['hospitalizado', 'alta'])->default('hospitalizado')->after('cedula');
        });

        // Establecer todos los registros existentes como 'hospitalizado' si no tienen estado
        DB::table('pacientes')->whereNull('estado')->update(['estado' => 'hospitalizado']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('pacientes') && Schema::hasColumn('pacientes', 'estado')) {
            Schema::table('pacientes', function (Blueprint $table) {
                $table->dropColumn('estado');
            });
        }
    }
};
