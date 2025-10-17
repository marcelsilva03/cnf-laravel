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
            $table->dropColumn([
                'sol_data',
                'sol_hora',
                'sol_tipo',
                'sol_fone1_ddd_sol',
                'sol_fone1_numero_sol',
                'sol_cartorio_id_ees',
                'sol_cartorio_id_ecd',
            ]);
            $table->string('sol_estado_cidade', 100)->nullable();
            $table->string('sol_tel_sol', 16)->nullable();
            $table->integer('sol_status')->default(0)->change();
            $table->integer('pag_metodo_escolhido')->default(0);
            $table->string('pag_token_transacao', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacao', function (Blueprint $table) {
            //
        });
    }
};
