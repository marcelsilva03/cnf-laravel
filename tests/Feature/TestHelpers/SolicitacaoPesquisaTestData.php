<?php

namespace Tests\Feature\TestHelpers;

use App\Models\Falecido;
use App\Models\Cartorio;
use App\Models\User;
use Illuminate\Support\Str;

class SolicitacaoPesquisaTestData
{
    /**
     * Create a complete test scenario with all required data
     */
    public static function createCompleteScenario(): array
    {
        // Create cartório
        $cartorio = Cartorio::create([
            'ccc_nome' => 'Cartório de Registro Civil - 1º Ofício de São Paulo',
            'ccc_cidade' => 'São Paulo',
            'ccc_uf' => 'SP',
            'ccc_endereco' => 'Rua do Cartório, 123, Centro',
            'ccc_bairro' => 'Centro',
            'ccc_cep' => '01000-000',
            'ccc_telefone' => '(11) 3333-4444',
            'ccc_email' => 'contato@cartorio1sp.com.br',
            'ccc_cnpj' => '12.345.678/0001-90',
            'ccc_tipo' => 1, // Registro Civil
            'ccc_comarca' => 'São Paulo',
            'ccc_nome_titular' => 'Dr. João Cartorário',
            'ccc_horario_funcionamento' => '9h às 17h',
        ]);

        // Create falecidos with different scenarios
        $falecidos = [
            // Complete record with all data
            'complete' => Falecido::create([
                'fal_uuid' => Str::uuid(),
                'fal_nome' => 'José da Silva Santos',
                'fal_cpf' => '12345678901',
                'fal_rg' => '123456789',
                'fal_titulo_eleitor' => '123456789012',
                'fal_data_nascimento' => '1950-05-15',
                'fal_data_falecimento' => '2025-05-20',
                'fal_nome_pai' => 'Antonio da Silva',
                'fal_nome_mae' => 'Maria Santos Silva',
                'fal_uf' => 'SP',
                'fal_cidade' => 'São Paulo',
                'fal_cartorio_obito' => $cartorio->ccc_nome,
                'fal_id_ccc' => $cartorio->ccc_id,
                'fal_co_livro' => 'A-123',
                'fal_co_folha' => '456',
                'fal_co_termo' => '78910',
                'fal_co_declaracao' => 'DEC-2025-001',
                'fal_local_sepultamento' => 'Cemitério da Paz',
                'fal_data_sepultamento' => strtotime('2025-05-22'),
                'fal_hora_sepultamento' => 1400, // 14:00
                'fal_local_velorio' => 'Capela 1 - Cemitério da Paz',
                'fal_estado_civil' => 2, // Casado
                'fal_tipo_local_falecimento' => 1, // Hospital
                'fal_idade' => '75 anos',
                'fal_status' => 1,
            ]),

            // Minimal record (only required fields)
            'minimal' => Falecido::create([
                'fal_uuid' => Str::uuid(),
                'fal_nome' => 'Maria Oliveira',
                'fal_data_falecimento' => '2025-04-10',
                'fal_uf' => 'RJ',
                'fal_cidade' => 'Rio de Janeiro',
                'fal_status' => 1,
            ]),

            // Record without CPF (only RG)
            'only_rg' => Falecido::create([
                'fal_uuid' => Str::uuid(),
                'fal_nome' => 'Pedro Almeida Costa',
                'fal_rg' => '987654321',
                'fal_data_nascimento' => '1960-10-20',
                'fal_data_falecimento' => '2025-03-15',
                'fal_nome_mae' => 'Ana Costa',
                'fal_uf' => 'MG',
                'fal_cidade' => 'Belo Horizonte',
                'fal_status' => 1,
            ]),

            // Recent death (for testing date filters)
            'recent' => Falecido::create([
                'fal_uuid' => Str::uuid(),
                'fal_nome' => 'João Pereira',
                'fal_cpf' => '98765432109',
                'fal_data_falecimento' => now()->subDays(2)->format('Y-m-d'),
                'fal_uf' => 'SP',
                'fal_cidade' => 'Santos',
                'fal_status' => 1,
            ]),
        ];

        // Create users with different roles
        $users = [
            'admin' => User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@cnf.test',
            ]),
            'pesquisador' => User::factory()->create([
                'name' => 'Pesquisador User',
                'email' => 'pesquisador@cnf.test',
            ]),
            'solicitante' => User::factory()->create([
                'name' => 'Solicitante User',
                'email' => 'solicitante@cnf.test',
            ]),
        ];

        // Assign roles
        $users['admin']->assignRole('admin');
        $users['pesquisador']->assignRole('pesquisador');

        return [
            'cartorio' => $cartorio,
            'falecidos' => $falecidos,
            'users' => $users,
        ];
    }

    /**
     * Create sample search request data
     */
    public static function getSampleSearchData(): array
    {
        return [
            'nome_fal' => 'José da Silva',
            'cpf' => '123.456.789-01',
            'rg' => '12.345.678-9',
            'titulo_eleitor' => '1234 5678 9012',
            'nascf' => '1950-05-15',
            'dfalec' => '2025-05-20',
            'nomepai' => 'Antonio da Silva',
            'nomemae' => 'Maria Santos Silva',
            'estado_obito' => 'SP',
            'cidade_obito' => 'São Paulo',
            'localfal' => 1, // Hospital
            'ecivil' => 2, // Casado
            'abrangencia' => 1, // Municipal
            'comentarios' => 'Necessário para processo de inventário',
            // Solicitante data
            'nomesol' => 'João Solicitante',
            'emailsol' => 'solicitante@email.com',
            'telsol' => '(11) 98765-4321',
        ];
    }

    /**
     * Create sample comunicado de óbito data
     */
    public static function getSampleComunicadoData(): array
    {
        return [
            'nome_sol' => 'Maria Comunicante',
            'email_sol' => 'comunicante@email.com',
            'tel_sol' => '(21) 99999-8888',
            'nome_fal' => 'Carlos Alberto Souza',
            'cpf_fal' => '111.222.333-44',
            'rg_fal' => '11.222.333-4',
            'titulo_eleitor' => '1111 2222 3333',
            'data_nascimento' => '1955-08-20',
            'data_obito' => '2025-05-18',
            'nome_pai_fal' => 'Alberto Souza',
            'nome_mae_fal' => 'Carla Souza',
            'estado_obito' => 'RJ',
            'cidade_obito' => 'Rio de Janeiro',
            'local_obito_tipo' => 2, // Casa
            'estado_civil' => 3, // Divorciado
            'obs' => 'Falecimento por causas naturais',
        ];
    }

    /**
     * Get valid payment methods
     */
    public static function getPaymentMethods(): array
    {
        return [
            1 => ['id' => 1, 'name' => 'PIX', 'icon' => 'pix'],
            2 => ['id' => 2, 'name' => 'Cartão de Crédito', 'icon' => 'credit-card'],
            3 => ['id' => 3, 'name' => 'Boleto', 'icon' => 'barcode'],
            4 => ['id' => 4, 'name' => 'Transferência Bancária', 'icon' => 'bank'],
        ];
    }

    /**
     * Get search abrangência options
     */
    public static function getAbrangenciaOptions(): array
    {
        return [
            1 => 'Municipal',
            2 => 'Estadual',
            3 => 'Nacional',
        ];
    }
}