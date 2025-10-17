<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetodoToFaturamentosTable extends Migration
{
    public function up()
    {
        Schema::table('faturamentos', function (Blueprint $table) {
            $table->string('metodo')->nullable()->after('valor');
        });
    }

    public function down()
    {
        Schema::table('faturamentos', function (Blueprint $table) {
            $table->dropColumn('metodo');
        });
    }
} 