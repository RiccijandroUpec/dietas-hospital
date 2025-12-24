<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('camas', function (Blueprint $table) {
            $table->unsignedBigInteger('servicio_id')->nullable()->after('codigo');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('camas', function (Blueprint $table) {
            $table->dropForeign(['servicio_id']);
            $table->dropColumn('servicio_id');
        });
    }
};
