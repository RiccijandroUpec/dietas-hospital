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
        Schema::table('registration_schedules', function (Blueprint $table) {
            $table->boolean('allow_out_of_schedule')->default(false)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_schedules', function (Blueprint $table) {
            $table->dropColumn('allow_out_of_schedule');
        });
    }
};
