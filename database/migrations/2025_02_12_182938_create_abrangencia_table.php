<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbrangenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abrangencia', function (Blueprint $table) {
            $table->id('abr_id');  // Chave primária
            $table->string('abr_desc', 255); // Descrição da abrangência
            $table->boolean('abr_status')->default(1)->comment('0 means inactive, 1 means active');
            $table->timestamps();
        });

        // Inserir os dados iniciais
        DB::table('abrangencia')->insert([
            ['abr_desc' => 'Nacional'],
            ['abr_desc' => 'Estadual'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abrangencia');
    }
}
