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
        Schema::create('preco_certidoes', function (Blueprint $table) {
            $table->string('UF', 3)->nullable();
            $table->decimal('BREVE_RELATO', 5, 2)->nullable();
            $table->decimal('INTEIRO_TEOR', 5, 2)->nullable();
            $table->integer('CNF_BREVE_RELATO')->nullable();
            $table->integer('CNF_INTEIRO_TEOR')->nullable();
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
