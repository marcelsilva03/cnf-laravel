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
        Schema::table('homenagens', function (Blueprint $table) {
            $table->uuid('hom_uuid_falecido')->after('hom_id_falecido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homenagens', function (Blueprint $table) {
            $table->dropColumn('hom_uuid_falecido');
        });
    }
};
