<?php

namespace Database\Seeders;

use App\Models\Falecido;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FalecidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Falecido::factory()->count(10)->create();
    }
}
