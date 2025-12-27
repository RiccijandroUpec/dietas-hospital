<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('registro_dietas', 'vajilla')) {
            Schema::table('registro_dietas', function (Blueprint $table) {
                $table->string('vajilla', 20)->default('normal')->after('tipo_comida');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('registro_dietas', 'vajilla')) {
            Schema::table('registro_dietas', function (Blueprint $table) {
                $table->dropColumn('vajilla');
            });
        }
    }
};
