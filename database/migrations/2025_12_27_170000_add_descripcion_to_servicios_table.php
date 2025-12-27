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
        if (Schema::hasTable('servicios')) {
            Schema::table('servicios', function (Blueprint $table) {
                if (!Schema::hasColumn('servicios', 'descripcion')) {
                    $table->text('descripcion')->nullable()->after('nombre');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('servicios')) {
            Schema::table('servicios', function (Blueprint $table) {
                if (Schema::hasColumn('servicios', 'descripcion')) {
                    $table->dropColumn('descripcion');
                }
            });
        }
    }
};
