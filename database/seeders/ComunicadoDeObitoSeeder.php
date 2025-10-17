<?php

namespace Database\Seeders;

use App\Models\ComunicadoDeObito;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComunicadoDeObitoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ComunicadoDeObito::factory()->count(10)->create();
    }
}
