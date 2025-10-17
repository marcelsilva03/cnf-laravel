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
            $table->dropColumn('fal_photo_id');
            $table->string('fal_foto', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('falecidos', function (Blueprint $table) {
            $table->unsignedBigInteger('fal_photo_id');
        });
    }
};
