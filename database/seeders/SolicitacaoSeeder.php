<?php

namespace Database\Seeders;

use App\Models\Solicitacao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SolicitacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Solicitacao::factory()->count(10)->create();
    }
}
