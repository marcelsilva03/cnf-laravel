<?php

namespace Database\Seeders;

use App\Models\Homenagem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomenagemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Homenagem::factory()->count(5)->create();
    }
}
