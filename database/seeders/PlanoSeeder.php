<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plano;

class PlanoSeeder extends Seeder
{
    public function run(): void
    {
        if (Plano::count() == 0) {
            Plano::insert([
                ['faixa_inicial' => 0, 'faixa_final' => 9999, 'preco_por_consulta' => 0.5591, 'ativo' => true],
                ['faixa_inicial' => 10000, 'faixa_final' => 19999, 'preco_por_consulta' => 0.5649, 'ativo' => true],
                ['faixa_inicial' => 20000, 'faixa_final' => 24999, 'preco_por_consulta' => 0.5660, 'ativo' => true],
                ['faixa_inicial' => 25000, 'faixa_final' => 49999, 'preco_por_consulta' => 0.1779, 'ativo' => true],
                ['faixa_inicial' => 50000, 'faixa_final' => 99999, 'preco_por_consulta' => 0.1678, 'ativo' => true],
                ['faixa_inicial' => 100000, 'faixa_final' => 199999, 'preco_por_consulta' => 0.1598, 'ativo' => true],
                ['faixa_inicial' => 200000, 'faixa_final' => 499999, 'preco_por_consulta' => 0.1557, 'ativo' => true],
                ['faixa_inicial' => 500000, 'faixa_final' => 999999, 'preco_por_consulta' => 0.1527, 'ativo' => true],
                ['faixa_inicial' => 1000000, 'faixa_final' => 4999999, 'preco_por_consulta' => 0.1499, 'ativo' => true],
                ['faixa_inicial' => 5000000, 'faixa_final' => 9999999, 'preco_por_consulta' => 0.1450, 'ativo' => true],
                ['faixa_inicial' => 10000000, 'faixa_final' => 29999999, 'preco_por_consulta' => 0.1370, 'ativo' => true],
                ['faixa_inicial' => 30000000, 'faixa_final' => null, 'preco_por_consulta' => 0.0170, 'ativo' => true],
            ]);
        }
    }
}
