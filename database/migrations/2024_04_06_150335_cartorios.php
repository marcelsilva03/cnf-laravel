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
        Schema::create('cartorios', function (Blueprint $table) {
            $table->mediumIncrements('ccc_id');
            $table->smallInteger('ccc_id_ecd')->unsigned()->nullable();
            $table->string('ccc_cidade', 100)->nullable();
            $table->string('ccc_uf', 3)->nullable();
            $table->string('ccc_email', 100)->nullable();
            $table->string('ccc_ultima_atualizacao', 15)->nullable();
            $table->string('ccc_nome', 200)->nullable();
            $table->string('ccc_nome_fantasia', 150)->nullable();
            $table->string('ccc_area_abrangencia', 200)->nullable();
            $table->string('ccc_atribuicoes', 300)->nullable();
            $table->string('ccc_comarca', 100)->nullable();
            $table->string('ccc_telefone', 70)->nullable();
            $table->string('ccc_fax', 50)->nullable();
            $table->string('ccc_obs', 1500)->nullable();
            $table->string('ccc_site', 1000)->nullable();
            $table->string('ccc_cnpj', 40)->nullable();
            $table->string('ccc_cns', 20)->nullable();
            $table->string('ccc_endereco', 1000)->nullable();
            $table->string('ccc_bairro', 1000)->nullable();
            $table->string('ccc_cep', 30)->nullable();
            $table->string('ccc_nome_titular', 100)->nullable();
            $table->string('ccc_nome_substituto', 100)->nullable();
            $table->string('ccc_nome_juiz', 100)->nullable();
            $table->string('ccc_horario_funcionamento', 100)->nullable();
            $table->string('ccc_entrancia', 50)->nullable();
            $table->tinyInteger('ccc_tipo')->unsigned()->nullable();
            $table->timestamps();

            $table->index('ccc_tipo');
            $table->index('ccc_uf');
            $table->index('ccc_cidade');
            $table->index('ccc_id_ecd');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
