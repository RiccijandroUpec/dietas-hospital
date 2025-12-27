<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dietas', function (Blueprint $table) {
            if (!Schema::hasColumn('dietas', 'tipo_dieta_id')) {
                $table->foreignId('tipo_dieta_id')->nullable()->after('id')->constrained('tipos_dieta')->onDelete('set null');
            }
            if (!Schema::hasColumn('dietas', 'subtipo_dieta_id')) {
                $table->foreignId('subtipo_dieta_id')->nullable()->after('tipo_dieta_id')->constrained('subtipos_dieta')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dietas', function (Blueprint $table) {
            if (Schema::hasColumn('dietas', 'subtipo_dieta_id')) {
                $table->dropForeign(['subtipo_dieta_id']);
                $table->dropColumn('subtipo_dieta_id');
            }
            if (Schema::hasColumn('dietas', 'tipo_dieta_id')) {
                $table->dropForeign(['tipo_dieta_id']);
                $table->dropColumn('tipo_dieta_id');
            }
        });
    }
};
