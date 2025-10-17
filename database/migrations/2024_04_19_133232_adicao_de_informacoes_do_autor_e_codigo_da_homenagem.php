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
        Schema::table('homenagens', function (Blueprint $table) {
            $table->string('hom_whatsapp', 20);
            $table->string('hom_email', 250);
            $table->tinyInteger('hom_parentesco');
            $table->string('hom_codigo', 8)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homenagens', function (Blueprint $table) {
            //
        });
    }
};
