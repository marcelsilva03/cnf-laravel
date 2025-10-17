<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new columns if they don't exist
        if (!Schema::hasColumn('user_roles', 'name')) {
            Schema::table('user_roles', function (Blueprint $table) {
                $table->string('name')->unique()->after('id');
            });
        }

        if (!Schema::hasColumn('user_roles', 'label')) {
            Schema::table('user_roles', function (Blueprint $table) {
                $table->string('label')->after('name');
            });
        }

        // Insert default roles if they don't exist
        if (!DB::table('user_roles')->where('name', 'admin')->exists()) {
            DB::table('user_roles')->insert([
                'name' => 'admin',
                'label' => 'Administrador',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('user_roles')->where('name', 'solicitante')->exists()) {
            DB::table('user_roles')->insert([
                'name' => 'solicitante',
                'label' => 'Solicitante',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the columns if they exist
        if (Schema::hasColumn('user_roles', 'name')) {
            Schema::table('user_roles', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }

        if (Schema::hasColumn('user_roles', 'label')) {
            Schema::table('user_roles', function (Blueprint $table) {
                $table->dropColumn('label');
            });
        }
    }
};
