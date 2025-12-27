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
        if (!Schema::hasTable('registro_refrigerios')) {
            return; // table must exist (created by earlier migration)
        }

        Schema::table('registro_refrigerios', function (Blueprint $table) {
            if (!Schema::hasColumn('registro_refrigerios', 'paciente_id')) {
                $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('registro_refrigerios', 'refrigerio_id')) {
                $table->foreignId('refrigerio_id')->constrained('refrigerios')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('registro_refrigerios', 'fecha')) {
                $table->date('fecha');
            }
            if (!Schema::hasColumn('registro_refrigerios', 'momento')) {
                $table->string('momento', 20);
            }
            if (!Schema::hasColumn('registro_refrigerios', 'observacion')) {
                $table->text('observacion')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('registro_refrigerios')) {
            return;
        }

        Schema::table('registro_refrigerios', function (Blueprint $table) {
            if (Schema::hasColumn('registro_refrigerios', 'paciente_id')) {
                $table->dropForeign(['paciente_id']);
                $table->dropColumn('paciente_id');
            }
            if (Schema::hasColumn('registro_refrigerios', 'refrigerio_id')) {
                $table->dropForeign(['refrigerio_id']);
                $table->dropColumn('refrigerio_id');
            }
            if (Schema::hasColumn('registro_refrigerios', 'fecha')) {
                $table->dropColumn('fecha');
            }
            if (Schema::hasColumn('registro_refrigerios', 'momento')) {
                $table->dropColumn('momento');
            }
            if (Schema::hasColumn('registro_refrigerios', 'observacion')) {
                $table->dropColumn('observacion');
            }
        });
    }
};
