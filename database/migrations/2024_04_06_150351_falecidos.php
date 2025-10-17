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
        Schema::create('falecidos', function (Blueprint $table) {
            $table->bigIncrements('fal_id');
            $table->unsignedTinyInteger('fal_status')->default(1)->comment('0 inativo, 1 ativo');
            $table->string('fal_descricao');
            $table->unsignedInteger('fal_id_cnf')->nullable();
            $table->unsignedBigInteger('fal_photo_id')->nullable();
            $table->unsignedBigInteger('fal_data_cadastro')->nullable();
            $table->unsignedInteger('fal_id_usr')->nullable();
            $table->unsignedInteger('fal_id_fun')->nullable();
            $table->unsignedInteger('fal_id_prf')->nullable();
            $table->unsignedSmallInteger('fal_domicilio_id_ecd')->nullable();
            $table->unsignedMediumInteger('fal_id_esc')->nullable();
            $table->unsignedMediumInteger('fal_id_crt')->nullable();
            $table->unsignedMediumInteger('fal_id_ccc')->nullable();
            $table->unsignedTinyInteger('fal_id_orf')->nullable();
            $table->unsignedTinyInteger('fal_id_cmn')->nullable();
            $table->string('fal_nome_abr', 3)->nullable();
            $table->string('fal_nome', 60)->nullable();
            $table->string('fal_apelido', 30)->nullable();
            $table->string('fal_doc', 14)->nullable();
            $table->unsignedTinyInteger('fal_sexo')->nullable();
            $table->string('fal_titulo_eleitor', 14)->nullable();
            $table->string('fal_data_falecimento', 100)->nullable();
            $table->string('fal_nome_pai', 60)->nullable();
            $table->string('fal_nome_mae', 60)->nullable();
            $table->text('fal_homenagem')->nullable();
            $table->text('fal_biografia')->nullable();
            $table->unsignedTinyInteger('fal_tipo_logo')->nullable();
            $table->unsignedSmallInteger('fal_obito_id_ecd')->nullable();
            $table->string('fal_cartorio_obito', 100)->nullable();
            $table->unsignedSmallInteger('fal_sepultamento_id_ecd')->nullable();
            $table->string('fal_local_sepultamento', 100)->nullable();
            $table->unsignedBigInteger('fal_data_sepultamento')->nullable();
            $table->unsignedMediumInteger('fal_hora_sepultamento')->nullable()->zerofill();
            $table->string('fal_local_velorio', 100)->nullable();
            $table->string('fal_data_nascimento', 100)->nullable();
            $table->unsignedBigInteger('fal_album_id')->nullable();
            $table->boolean('fal_importada')->default(0);
            $table->string('fal_uf', 150)->nullable();
            $table->string('fal_cidade', 150)->nullable();
            $table->string('fal_orkut', 150)->nullable();
            $table->string('fal_youtube', 150)->nullable();
            $table->string('fal_co_livro', 30)->nullable();
            $table->string('fal_co_folha', 30)->nullable();
            $table->string('fal_co_termo', 30)->nullable();
            $table->string('fal_co_declaracao', 30)->nullable();
            $table->tinyInteger('fal_controle1')->nullable();
            $table->tinyInteger('fal_controle2')->nullable();
            $table->string('fal_idade', 11)->nullable();
            $table->text('fal_obs')->nullable();
//            $table->unsignedBigInteger('fal_tit_ele_aux')->nullable();
            $table->timestamps();

            $table->unique(['fal_nome', 'fal_data_falecimento', 'fal_obito_id_ecd'], 'nome_obito_cidade');
            $table->index('fal_doc');
            $table->index(['fal_cidade', 'fal_uf'], 'cidade_uf');
            $table->index('fal_nome_abr');
            $table->index('fal_id_orf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('falecidos');
    }
};
