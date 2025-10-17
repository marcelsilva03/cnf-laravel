<?php

namespace Database\Seeders;

use App\Models\Cartorio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar cartórios existentes
        DB::table('cartorios')->truncate();
        
        // Cartórios específicos para cidades comuns
        $cartorios = [
            // São Paulo - SP
            [
                'ccc_cidade' => 'São Paulo',
                'ccc_uf' => 'SP',
                'ccc_nome' => '1º Cartório de Registro Civil de São Paulo',
                'ccc_email' => 'cartorio1sp@exemplo.com',
                'ccc_telefone' => '(11) 3333-1111',
                'ccc_endereco' => 'Rua da Consolação, 100',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '01302-000',
                'ccc_tipo' => 1,
            ],
            [
                'ccc_cidade' => 'São Paulo',
                'ccc_uf' => 'SP',
                'ccc_nome' => '2º Cartório de Registro Civil de São Paulo',
                'ccc_email' => 'cartorio2sp@exemplo.com',
                'ccc_telefone' => '(11) 3333-2222',
                'ccc_endereco' => 'Av. Paulista, 200',
                'ccc_bairro' => 'Bela Vista',
                'ccc_cep' => '01310-000',
                'ccc_tipo' => 1,
            ],
            // Rio de Janeiro - RJ
            [
                'ccc_cidade' => 'Rio de Janeiro',
                'ccc_uf' => 'RJ',
                'ccc_nome' => '1º Cartório de Registro Civil do Rio de Janeiro',
                'ccc_email' => 'cartorio1rj@exemplo.com',
                'ccc_telefone' => '(21) 2222-1111',
                'ccc_endereco' => 'Rua da Carioca, 50',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '20050-000',
                'ccc_tipo' => 1,
            ],
            // Belo Horizonte - MG
            [
                'ccc_cidade' => 'Belo Horizonte',
                'ccc_uf' => 'MG',
                'ccc_nome' => '1º Cartório de Registro Civil de Belo Horizonte',
                'ccc_email' => 'cartorio1bh@exemplo.com',
                'ccc_telefone' => '(31) 3333-1111',
                'ccc_endereco' => 'Av. Afonso Pena, 300',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '30112-000',
                'ccc_tipo' => 1,
            ],
            // Porto Alegre - RS
            [
                'ccc_cidade' => 'Porto Alegre',
                'ccc_uf' => 'RS',
                'ccc_nome' => '1º Cartório de Registro Civil de Porto Alegre',
                'ccc_email' => 'cartorio1poa@exemplo.com',
                'ccc_telefone' => '(51) 3333-1111',
                'ccc_endereco' => 'Rua dos Andradas, 400',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '90020-000',
                'ccc_tipo' => 1,
            ],
            // Brasília - DF
            [
                'ccc_cidade' => 'Brasília',
                'ccc_uf' => 'DF',
                'ccc_nome' => '1º Cartório de Registro Civil de Brasília',
                'ccc_email' => 'cartorio1df@exemplo.com',
                'ccc_telefone' => '(61) 3333-1111',
                'ccc_endereco' => 'SCS Quadra 1, Bloco A',
                'ccc_bairro' => 'Asa Sul',
                'ccc_cep' => '70300-000',
                'ccc_tipo' => 1,
            ],
            // Salvador - BA
            [
                'ccc_cidade' => 'Salvador',
                'ccc_uf' => 'BA',
                'ccc_nome' => '1º Cartório de Registro Civil de Salvador',
                'ccc_email' => 'cartorio1ssa@exemplo.com',
                'ccc_telefone' => '(71) 3333-1111',
                'ccc_endereco' => 'Rua Chile, 500',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '40070-000',
                'ccc_tipo' => 1,
            ],
            // Fortaleza - CE
            [
                'ccc_cidade' => 'Fortaleza',
                'ccc_uf' => 'CE',
                'ccc_nome' => '1º Cartório de Registro Civil de Fortaleza',
                'ccc_email' => 'cartorio1for@exemplo.com',
                'ccc_telefone' => '(85) 3333-1111',
                'ccc_endereco' => 'Rua Major Facundo, 600',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '60025-000',
                'ccc_tipo' => 1,
            ],
            // Recife - PE
            [
                'ccc_cidade' => 'Recife',
                'ccc_uf' => 'PE',
                'ccc_nome' => '1º Cartório de Registro Civil de Recife',
                'ccc_email' => 'cartorio1rec@exemplo.com',
                'ccc_telefone' => '(81) 3333-1111',
                'ccc_endereco' => 'Rua do Imperador, 700',
                'ccc_bairro' => 'Santo Antônio',
                'ccc_cep' => '50010-000',
                'ccc_tipo' => 1,
            ],
            // Manaus - AM
            [
                'ccc_cidade' => 'Manaus',
                'ccc_uf' => 'AM',
                'ccc_nome' => '1º Cartório de Registro Civil de Manaus',
                'ccc_email' => 'cartorio1mao@exemplo.com',
                'ccc_telefone' => '(92) 3333-1111',
                'ccc_endereco' => 'Av. Eduardo Ribeiro, 800',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '69010-000',
                'ccc_tipo' => 1,
            ],
            // Goiânia - GO
            [
                'ccc_cidade' => 'Goiânia',
                'ccc_uf' => 'GO',
                'ccc_nome' => '1º Cartório de Registro Civil de Goiânia',
                'ccc_email' => 'cartorio1gyn@exemplo.com',
                'ccc_telefone' => '(62) 3333-1111',
                'ccc_endereco' => 'Av. Goiás, 100',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '74003-010',
                'ccc_tipo' => 1,
            ],
            [
                'ccc_cidade' => 'Goiânia',
                'ccc_uf' => 'GO',
                'ccc_nome' => '2º Cartório de Registro Civil de Goiânia',
                'ccc_email' => 'cartorio2gyn@exemplo.com',
                'ccc_telefone' => '(62) 3333-2222',
                'ccc_endereco' => 'Rua 3, 200',
                'ccc_bairro' => 'Setor Central',
                'ccc_cep' => '74023-010',
                'ccc_tipo' => 1,
            ],
            // Valparaíso de Goiás - GO
            [
                'ccc_cidade' => 'Valparaíso de Goiás',
                'ccc_uf' => 'GO',
                'ccc_nome' => 'Cartório de Registro Civil de Valparaíso de Goiás',
                'ccc_email' => 'cartoriovalpgo@exemplo.com',
                'ccc_telefone' => '(61) 3333-3333',
                'ccc_endereco' => 'Av. Central, 300',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '72870-000',
                'ccc_tipo' => 1,
            ],
            // Aparecida de Goiânia - GO
            [
                'ccc_cidade' => 'Aparecida de Goiânia',
                'ccc_uf' => 'GO',
                'ccc_nome' => 'Cartório de Registro Civil de Aparecida de Goiânia',
                'ccc_email' => 'cartorioaparecida@exemplo.com',
                'ccc_telefone' => '(62) 3333-4444',
                'ccc_endereco' => 'Av. Independência, 400',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '74905-000',
                'ccc_tipo' => 1,
            ],
            // Anápolis - GO
            [
                'ccc_cidade' => 'Anápolis',
                'ccc_uf' => 'GO',
                'ccc_nome' => 'Cartório de Registro Civil de Anápolis',
                'ccc_email' => 'cartorioanapolis@exemplo.com',
                'ccc_telefone' => '(62) 3333-5555',
                'ccc_endereco' => 'Rua Marechal Deodoro, 500',
                'ccc_bairro' => 'Centro',
                'ccc_cep' => '75110-010',
                'ccc_tipo' => 1,
            ],
        ];

        foreach ($cartorios as $cartorio) {
            Cartorio::create($cartorio);
        }
        
        // Criar alguns cartórios adicionais usando factory
        Cartorio::factory()->count(20)->create();
    }
}
