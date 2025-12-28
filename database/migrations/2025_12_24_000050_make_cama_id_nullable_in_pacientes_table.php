<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (Schema::hasTable('pacientes') && Schema::hasColumn('pacientes', 'cama_id')) {
            Schema::table('pacientes', function (Blueprint $table) {
                $table->unsignedBigInteger('cama_id')->nullable()->change();
            });
        }
    }
    public function down() {
        if (Schema::hasTable('pacientes') && Schema::hasColumn('pacientes', 'cama_id')) {
            Schema::table('pacientes', function (Blueprint $table) {
                $table->unsignedBigInteger('cama_id')->nullable(false)->change();
            });
        }
    }
};
