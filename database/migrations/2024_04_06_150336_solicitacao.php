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
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->id('sol_id');
            $table->string('sol_data', 8)->nullable();
            $table->string('sol_hora', 6)->nullable();
            $table->string('sol_tipo', 10)->nullable();
            $table->string('sol_status', 10)->nullable();
            $table->string('sol_nome_sol', 60)->nullable();
            $table->string('sol_fone1_ddd_sol', 2)->nullable();
            $table->string('sol_fone1_numero_sol', 12)->nullable();
            $table->string('sol_email_sol', 60)->nullable();
            $table->string('sol_nome_fal', 60)->nullable();
            $table->string('sol_rg_fal', 12)->nullable();
            $table->string('sol_cpf_fal', 11)->nullable();
            $table->string('sol_nome_pai_fal', 60)->nullable();
            $table->string('sol_nome_mae_fal', 60)->nullable();
            $table->string('sol_data_nascimento', 10)->nullable();
            $table->string('sol_data_obito', 10)->nullable();
            $table->string('sol_cartorio_id_ees', 12)->nullable();
            $table->string('sol_cartorio_id_ecd', 60)->nullable();
            $table->string('sol_local_obito_tipo', 20)->nullable();
            $table->string('sol_valor', 12)->nullable();
            $table->string('sol_obs', 500)->nullable();
            $table->string('sol_estado_civil', 20)->nullable();
            $table->string('sol_titulo_eleitor', 12)->nullable();
            $table->uuid('pag_code')->default(DB::raw('(UUID())'))->unique();
            $table->string('pag_date', 50)->nullable();
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
