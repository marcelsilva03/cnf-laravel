<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSolIdAbrToSolicitacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adicionar a coluna sol_id_abr na tabela solicitacoes
        Schema::table('solicitacoes', function (Blueprint $table) {
            $table->unsignedBigInteger('sol_id_abr')->nullable();
        });

        // Atualizar valores da coluna sol_id_abr
        DB::table('solicitacoes')->where('sol_id_abr', 0)->update(['sol_id_abr' => 1]);

        // Adicionar a chave estrangeira
        Schema::table('solicitacoes', function (Blueprint $table) {
            $table->foreign('sol_id_abr')->references('abr_id')->on('abrangencia')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remover a chave estrangeira
        Schema::table('solicitacoes', function (Blueprint $table) {
            $table->dropForeign(['sol_id_abr']);
        });

        // Remover a coluna sol_id_abr
        Schema::table('solicitacoes', function (Blueprint $table) {
            $table->dropColumn('sol_id_abr');
        });
    }
}
