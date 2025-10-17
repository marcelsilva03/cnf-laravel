<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_key', 64)->unique();
            $table->string('api_secret', 64); // opcional, caso precise gerar HMAC, etc.
            $table->integer('request_limit')->default(1000); // Limite de requisições
            $table->integer('requests_made')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_clients');
    }
};
