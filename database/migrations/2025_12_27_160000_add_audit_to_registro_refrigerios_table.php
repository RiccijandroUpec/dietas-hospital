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
        if (Schema::hasTable('registro_refrigerios')) {
            Schema::table('registro_refrigerios', function (Blueprint $table) {
                if (!Schema::hasColumn('registro_refrigerios', 'created_by')) {
                    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('registro_refrigerios', 'updated_by')) {
                    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('registro_refrigerios')) {
            Schema::table('registro_refrigerios', function (Blueprint $table) {
                if (Schema::hasColumn('registro_refrigerios', 'created_by')) {
                    $table->dropForeignIdFor(\App\Models\User::class, 'created_by');
                    $table->dropColumn('created_by');
                }
                if (Schema::hasColumn('registro_refrigerios', 'updated_by')) {
                    $table->dropForeignIdFor(\App\Models\User::class, 'updated_by');
                    $table->dropColumn('updated_by');
                }
            });
        }
    }
};
