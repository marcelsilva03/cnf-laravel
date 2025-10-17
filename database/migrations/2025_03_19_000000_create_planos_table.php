<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanosTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('planos')) {
            Schema::create('planos', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('faixa_inicial');
                $table->bigInteger('faixa_final')->nullable();
                $table->decimal('preco_por_consulta', 10, 4);
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('planos');
    }
}
