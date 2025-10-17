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
        Schema::create('homenagens', function (Blueprint $table) {
            $table->bigIncrements('hom_id');
            $table->unsignedBigInteger('hom_id_falecido');
            $table->string('hom_nome_autor', 100);
            $table->string('hom_cpf_autor', 11);
            $table->string('hom_url_foto', 250);
            $table->string('hom_url_fundo', 250);
            $table->string('hom_mensagem', 1275);
            $table->integer('hom_status')->default(0)->comment('0 pendente, 1 publicado, 2: removido');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homenagens');
    }
};
