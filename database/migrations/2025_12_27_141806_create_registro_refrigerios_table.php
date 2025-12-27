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
            Schema::create('registro_refrigerios', function (Blueprint $table) {
                $table->id();
                $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
                $table->foreignId('refrigerio_id')->constrained('refrigerios')->cascadeOnDelete();
                $table->date('fecha');
                $table->string('momento'); // mañana, tarde, noche
                $table->text('observacion')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_refrigerios');
    }
};
