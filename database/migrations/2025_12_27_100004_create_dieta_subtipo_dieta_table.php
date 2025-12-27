<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dieta_subtipo_dieta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dieta_id')->constrained('dietas')->onDelete('cascade');
            $table->foreignId('subtipo_dieta_id')->constrained('subtipos_dieta')->onDelete('cascade');
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['dieta_id', 'subtipo_dieta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dieta_subtipo_dieta');
    }
};
