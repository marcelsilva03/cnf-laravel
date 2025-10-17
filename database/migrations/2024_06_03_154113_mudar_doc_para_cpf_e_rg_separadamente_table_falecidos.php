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
            $table->dropColumn('fal_doc');
            $table->string('fal_rg', 15)->nullable();;
            $table->string('fal_cpf', 11)->nullable();;
            $table->string('fal_tipo_local_falecimento', 20)->nullable();;
            $table->string('fal_estado_civil', 20)->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('falecidos', function (Blueprint $table) {
            $table->string('fal_doc', 14);
            $table->dropColumn(['fal_cpf', 'fal_rg']);
        });
    }
};
