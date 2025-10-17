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
        Schema::table('comunicados_de_erro', function (Blueprint $table) {
            $table->unsignedBigInteger('id_falecido')->after('id');
            $table->char('uuid_falecido', 36)->after('id_falecido');
            $table->string('email_comunicante', 100)->after('uuid_falecido');
            $table->string('nome_comunicante', 100)->after('email_comunicante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comunicados_de_erro', function (Blueprint $table) {
            //
        });
    }
};
