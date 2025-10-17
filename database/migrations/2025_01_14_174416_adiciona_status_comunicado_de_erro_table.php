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
        Schema::table('comunicados_de_erro', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(0)->comment('0: PENDENTE, 1: RESOLVIDO, 2: REJEITADO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comunicado_de_erro', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
