<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('subtipos_dieta')) {
            Schema::create('subtipos_dieta', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tipo_dieta_id')->constrained('tipos_dieta')->onDelete('cascade');
                $table->string('nombre');
                $table->text('descripcion')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('subtipos_dieta');
    }
};
