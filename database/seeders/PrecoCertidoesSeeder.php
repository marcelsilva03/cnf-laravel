<?php

namespace Database\Seeders;

use App\Models\PrecoCertidoes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrecoCertidoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrecoCertidoes::factory()->count(10)->create();
    }
}
