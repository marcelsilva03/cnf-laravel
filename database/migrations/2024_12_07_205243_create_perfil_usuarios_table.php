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
        Schema::create('perfil_usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('old_id')->unique();
            $table->bigInteger('data_cadastro')->nullable();
            $table->boolean('ativo')->default(false);
            $table->string('login', 250)->nullable();
            $table->string('cod_altera_senha')->nullable();
            $table->string('email', 45)->nullable();
            $table->boolean('email_confirmado')->nullable()->default(false);
            $table->string('nome', 70)->nullable();
            $table->string('responsavel', 70)->nullable();
            $table->bigInteger('data_nascimento')->nullable();
            $table->unsignedTinyInteger('sexo')->nullable();
            $table->string('cpf', 20)->nullable();
            $table->string('rg', 16)->nullable();
            $table->string('endereco', 60)->nullable();
            $table->string('endereco_numero', 10)->nullable();
            $table->string('endereco_complemento', 50)->nullable();
            $table->string('endereco_bairro', 35)->nullable();
            $table->string('endereco_cep', 12)->nullable();
            $table->unsignedBigInteger('endereco_id_ecd')->nullable();
            $table->string('fone_numero', 20)->nullable();
            $table->unsignedBigInteger('id_fun')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_usuarios');
    }
};
