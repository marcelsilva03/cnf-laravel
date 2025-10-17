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
        Schema::create('usuario', function (Blueprint $table) {
            $table->increments('usr_id');
            $table->unsignedBigInteger('usr_data_cadastro')->nullable();
            $table->tinyInteger('usr_ativo')->nullable();
            $table->string('usr_login', 250)->nullable()->collation('latin1_general_ci');
            $table->string('usr_senha', 32)->nullable()->collation('latin1_general_ci');
            $table->unsignedInteger('usr_cod_altera_senha')->nullable();
            $table->unsignedTinyInteger('usr_su')->nullable();
            $table->string('usr_email', 45)->nullable()->collation('latin1_general_ci');
            $table->unsignedSmallInteger('usr_email_confirmado')->nullable();
            $table->string('usr_nome', 70)->nullable()->collation('latin1_general_ci');
            $table->string('usr_responsavel', 70)->nullable()->collation('latin1_general_ci');
            $table->unsignedInteger('usr_data_nascimento')->nullable();
            $table->unsignedTinyInteger('usr_sexo')->nullable();
            $table->string('usr_cpf', 20)->nullable()->collation('latin1_general_ci');
            $table->string('usr_rg', 16)->nullable()->collation('latin1_general_ci');
            $table->string('usr_endereco', 60)->nullable()->collation('latin1_general_ci');
            $table->string('usr_endereco_numero', 10)->nullable()->collation('latin1_general_ci');
            $table->string('usr_endereco_complemento', 50)->nullable()->collation('latin1_general_ci');
            $table->string('usr_endereco_bairro', 35)->nullable()->collation('latin1_general_ci');
            $table->string('usr_endereco_cep', 12)->nullable()->collation('latin1_general_ci');
            $table->unsignedInteger('usr_endereco_id_ecd')->nullable();
            $table->string('usr_fone_numero', 13)->nullable()->collation('latin1_general_ci');
            $table->unsignedInteger('usr_id_fun')->nullable();
            $table->timestamps();
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
