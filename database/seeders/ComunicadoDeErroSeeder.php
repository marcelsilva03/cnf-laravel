<?php

namespace Database\Seeders;

use App\Models\ComunicadoDeErro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComunicadoDeErroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ComunicadoDeErro::factory()->count(10)->create();
    }
}
