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
        Schema::table('refrigerios', function (Blueprint $table) {
            if (!Schema::hasColumn('refrigerios', 'nombre')) {
                $table->string('nombre')->after('id');
            }
            if (!Schema::hasColumn('refrigerios', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('nombre');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refrigerios', function (Blueprint $table) {
            if (Schema::hasColumn('refrigerios', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
            if (Schema::hasColumn('refrigerios', 'nombre')) {
                $table->dropColumn('nombre');
            }
        });
    }
};
