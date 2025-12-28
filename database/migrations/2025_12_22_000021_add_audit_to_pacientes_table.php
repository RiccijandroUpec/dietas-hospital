<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('pacientes')) {
            Schema::table('pacientes', function (Blueprint $table) {
                if (!Schema::hasColumn('pacientes', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('cama_id');
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                }
                if (!Schema::hasColumn('pacientes', 'updated_by')) {
                    $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                    $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('pacientes')) {
            Schema::table('pacientes', function (Blueprint $table) {
                if (Schema::hasColumn('pacientes', 'created_by')) {
                    $table->dropForeign(['created_by']);
                    $table->dropColumn('created_by');
                }
                if (Schema::hasColumn('pacientes', 'updated_by')) {
                    $table->dropForeign(['updated_by']);
                    $table->dropColumn('updated_by');
                }
            });
        }
    }
};
