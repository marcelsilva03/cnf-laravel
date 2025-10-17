<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaturamentosTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('faturamentos')) {
            Schema::create('faturamentos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users');
                $table->decimal('valor', 10, 2);
                $table->date('data_pagamento');
                $table->text('descricao')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('faturamentos');
    }
}
