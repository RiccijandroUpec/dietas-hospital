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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('action'); // create, update, delete, login, logout
            $table->string('model_type')->nullable(); // Paciente, RegistroDieta, etc
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('description');
            $table->json('changes')->nullable(); // Para guardar qué cambió
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('action');
            $table->index('model_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
