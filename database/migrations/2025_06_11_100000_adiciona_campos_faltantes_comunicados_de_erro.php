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
            // Adicionando campos faltantes que são usados pelo modelo
            if (!Schema::hasColumn('comunicados_de_erro', 'id_falecido')) {
                $table->unsignedInteger('id_falecido')->nullable();
            }
            
            if (!Schema::hasColumn('comunicados_de_erro', 'uuid_falecido')) {
                $table->string('uuid_falecido')->nullable();
            }
            
            if (!Schema::hasColumn('comunicados_de_erro', 'email_comunicante')) {
                $table->string('email_comunicante')->nullable();
            }
            
            if (!Schema::hasColumn('comunicados_de_erro', 'nome_comunicante')) {
                $table->string('nome_comunicante')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comunicados_de_erro', function (Blueprint $table) {
            $table->dropColumn([
                'id_falecido',
                'uuid_falecido',
                'email_comunicante',
                'nome_comunicante'
            ]);
        });
    }
}; 