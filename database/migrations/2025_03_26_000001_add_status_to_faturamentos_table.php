<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFaturamentosTable extends Migration
{
    public function up()
    {
        Schema::table('faturamentos', function (Blueprint $table) {
            $table->string('status')->default('pendente')->after('metodo');
        });
    }

    public function down()
    {
        Schema::table('faturamentos', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
} 