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
        Schema::create('comunicados_de_obito', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_sol', 60)->nullable();
            $table->string('fone_sol', 1100)->nullable();
            $table->string('email_sol', 60)->nullable();
            $table->string('nome_fal', 60)->nullable();
            $table->string('cpf_fal', 11)->nullable();
            $table->string('rg_fal', 12)->nullable();
            $table->string('titulo_eleitor', 12)->nullable();
            $table->string('nome_pai_fal', 60)->nullable();
            $table->string('nome_mae_fal', 60)->nullable();
            $table->string('cidade_estado_obito', 100)->nullable();
            $table->integer('cartorio_id')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->date('data_obito')->nullable();
            $table->integer('local_obito_tipo')->nullable()->comment('Definido em config/constants');
            $table->string('estado_civil', 10)->nullable()->comment('Definido em config/constants');
            $table->tinyInteger('sexo')->comment('1 masculino, 2 feminino');
            $table->string('obs', 500)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: pendente, 1: aceito, 2: rejeitado');
            $table->string('livro', 255)->nullable();
            $table->string('folha', 255)->nullable();
            $table->string('termo', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunicados_de_obito');
    }
};
