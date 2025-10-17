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
        Schema::table('falecidos', function (Blueprint $table) {
            $table->uuid('fal_uuid')->after('fal_id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('falecidos', function (Blueprint $table) {
            $table->dropColumn('fal_uuid');
        });
    }
};
