<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiRequisicoesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('api_requisicoes')) {
            Schema::create('api_requisicoes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users');
                $table->string('cpf_consultado');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('api_requisicoes');
    }
}
