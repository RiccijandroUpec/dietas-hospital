<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            // Update existing records with older default 'user' to 'usuario'
            DB::table('users')->where('role', 'user')->update(['role' => 'usuario']);

            // Change default value for the column
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('usuario')->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->change();
            });
        }
    }
};
