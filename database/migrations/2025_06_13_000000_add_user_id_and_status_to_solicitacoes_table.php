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
        Schema::table('solicitacoes', function (Blueprint $table) {
            // Adicionar coluna user_id como chave estrangeira
            $table->unsignedBigInteger('user_id')->nullable()->after('sol_id_abr');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // Adicionar coluna status com valor padrão 0 (PENDENTE)
            $table->tinyInteger('status')->default(0)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacoes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('status');
        });
    }
}; 