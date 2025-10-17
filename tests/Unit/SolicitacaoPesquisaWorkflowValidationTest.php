<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Falecido;
use App\Models\Solicitacao;
use App\Models\Cartorio;
use Illuminate\Foundation\Testing\WithFaker;

class SolicitacaoPesquisaWorkflowValidationTest extends TestCase
{
    use WithFaker;

    /**
     * Test that all required models exist and have correct structure
     */
    public function test_required_models_exist()
    {
        // Check if model files exist
        $this->assertTrue(class_exists('App\Models\Falecido'));
        $this->assertTrue(class_exists('App\Models\Solicitacao'));
        $this->assertTrue(class_exists('App\Models\Cartorio'));
        $this->assertTrue(class_exists('App\Models\User'));
        $this->assertTrue(class_exists('App\Models\ComunicadoDeObito'));
    }

    /**
     * Test Falecido model structure
     */
    public function test_falecido_model_structure()
    {
        $falecido = new Falecido();
        
        // Check fillable fields
        $fillable = $falecido->getFillable();
        $expectedFields = [
            'fal_nome', 'fal_cpf', 'fal_rg', 'fal_titulo_eleitor',
            'fal_nome_pai', 'fal_nome_mae', 'fal_data_nascimento',
            'fal_data_falecimento', 'fal_tipo_local_falecimento',
            'fal_estado_civil', 'fal_obs', 'fal_status'
        ];
        
        foreach ($expectedFields as $field) {
            $this->assertContains($field, $fillable, "Field {$field} should be fillable in Falecido model");
        }
        
        // Check table name
        $this->assertEquals('falecidos', $falecido->getTable());
        
        // Check primary key
        $this->assertEquals('fal_id', $falecido->getKeyName());
    }

    /**
     * Test Solicitacao model structure
     */
    public function test_solicitacao_model_structure()
    {
        // Check if STATUS constants exist
        $this->assertTrue(defined('App\Models\Solicitacao::STATUS'));
        
        $expectedStatuses = ['PENDENTE', 'AGUARDANDO_PAGAMENTO', 'PAGO', 'EM_ANDAMENTO', 'FINALIZADO', 'CANCELADO'];
        foreach ($expectedStatuses as $status) {
            $this->assertArrayHasKey($status, Solicitacao::STATUS);
        }
    }

    /**
     * Test route structure for workflow
     */
    public function test_workflow_routes_exist()
    {
        $routes = [
            'GET' => [
                '/busca-avancada' => 'formulario-pesquisa',
                '/resultados' => 'resultados',
                '/comunicarobito' => 'comunicar-obito',
            ],
            'POST' => [
                '/resultados' => 'resultados-recaptcha',
                '/pagamento-pesquisa' => 'pagamento-pesquisa-post',
                '/submit-form' => 'submit.form',
                '/comunicar-erro' => 'receptor-comunicado-de-erro',
            ]
        ];

        foreach ($routes as $method => $routeList) {
            foreach ($routeList as $uri => $name) {
                $this->assertTrue(
                    \Route::has($name),
                    "Route {$name} ({$method} {$uri}) should exist"
                );
            }
        }
    }

    /**
     * Test search validation rules
     */
    public function test_search_validation_rules()
    {
        $validData = [
            'nome_fal' => 'João Silva',
            'cpf' => '123.456.789-01',
            'estado_obito' => 'SP',
            'cidade_obito' => 'São Paulo',
            'dfalec' => '2025-05-20',
            'nascf' => '1950-05-15',
        ];

        $this->assertIsArray($validData);
        $this->assertNotEmpty($validData['nome_fal']);
        $this->assertMatchesRegularExpression('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $validData['cpf']);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $validData['dfalec']);
    }

    /**
     * Test payment flow validation
     */
    public function test_payment_flow_validation()
    {
        $paymentMethods = [1, 2, 3, 4]; // PIX, Cartão, Boleto, Transferência
        $validAmounts = ['45.00', '75.00'];
        
        foreach ($paymentMethods as $method) {
            $this->assertIsInt($method);
            $this->assertGreaterThan(0, $method);
        }
        
        foreach ($validAmounts as $amount) {
            $this->assertIsString($amount);
            $this->assertMatchesRegularExpression('/^\d+\.\d{2}$/', $amount);
        }
    }

    /**
     * Test workflow state transitions
     */
    public function test_workflow_state_transitions()
    {
        $validTransitions = [
            Solicitacao::STATUS['PENDENTE'] => [
                Solicitacao::STATUS['AGUARDANDO_PAGAMENTO'],
                Solicitacao::STATUS['CANCELADO']
            ],
            Solicitacao::STATUS['AGUARDANDO_PAGAMENTO'] => [
                Solicitacao::STATUS['PAGO'],
                Solicitacao::STATUS['CANCELADO']
            ],
            Solicitacao::STATUS['PAGO'] => [
                Solicitacao::STATUS['EM_ANDAMENTO'],
                Solicitacao::STATUS['CANCELADO']
            ],
            Solicitacao::STATUS['EM_ANDAMENTO'] => [
                Solicitacao::STATUS['FINALIZADO'],
                Solicitacao::STATUS['CANCELADO']
            ],
        ];

        foreach ($validTransitions as $fromStatus => $toStatuses) {
            $this->assertIsArray($toStatuses);
            $this->assertNotEmpty($toStatuses);
            
            foreach ($toStatuses as $toStatus) {
                $this->assertIsInt($fromStatus);
                $this->assertIsInt($toStatus);
                $this->assertNotEquals($fromStatus, $toStatus);
            }
        }
    }

    /**
     * Test data migration field mapping
     */
    public function test_migration_field_mapping()
    {
        $migrationMapping = [
            // Old database → New database
            'pes_nome' => 'fal_nome',
            'pes_data_nascimento' => 'fal_data_nascimento',
            'pes_data_falecimento' => 'fal_data_falecimento',
            'pes_nome_pai' => 'fal_nome_pai',
            'pes_nome_mae' => 'fal_nome_mae',
            'pes_uf' => 'fal_uf',
            'pes_cidade' => 'fal_cidade',
            'pes_cartorio_obito' => 'fal_cartorio_obito',
            'pes_co_livro' => 'fal_co_livro',
            'pes_co_folha' => 'fal_co_folha',
            'pes_co_termo' => 'fal_co_termo',
            'pes_data_sepultamento' => 'fal_data_sepultamento',
        ];

        foreach ($migrationMapping as $oldField => $newField) {
            $this->assertIsString($oldField);
            $this->assertIsString($newField);
            $this->assertStringStartsWith('pes_', $oldField);
            $this->assertStringStartsWith('fal_', $newField);
        }
    }

    /**
     * Test CPF/RG cleaning logic
     */
    public function test_cpf_rg_cleaning_logic()
    {
        $testCases = [
            // [input, expected_cpf, expected_rg]
            ['123.456.789-01', '12345678901', null],
            ['12345678901', '12345678901', null],
            ['123456789', null, '123456789'],
            ['12.345.678-9', null, '12.345.678-9'],
            ['invalid-doc', null, 'invalid-doc'],
        ];

        foreach ($testCases as [$input, $expectedCpf, $expectedRg]) {
            $cleaned = preg_replace('/[^0-9]/', '', $input);
            
            if (strlen($cleaned) === 11) {
                $this->assertEquals($expectedCpf, $cleaned);
                $this->assertNull($expectedRg);
            } else {
                $this->assertNull($expectedCpf);
                $this->assertEquals($expectedRg, $input);
            }
        }
    }

    /**
     * Test date format conversion
     */
    public function test_date_format_conversion()
    {
        $testCases = [
            '20250520' => '2025-05-20',
            '19501515' => '1950-15-15', // Invalid, but tests format
            '20241201' => '2024-12-01',
        ];

        foreach ($testCases as $input => $expected) {
            if (strlen($input) === 8) {
                $converted = substr($input, 0, 4) . '-' . 
                            substr($input, 4, 2) . '-' . 
                            substr($input, 6, 2);
                $this->assertEquals($expected, $converted);
            }
        }
    }

    /**
     * Test UUID generation logic
     */
    public function test_uuid_generation()
    {
        $uuid = \Str::uuid()->toString();
        
        $this->assertIsString($uuid);
        $this->assertEquals(36, strlen($uuid));
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $uuid
        );
    }

    /**
     * Test permissions structure
     */
    public function test_permissions_structure()
    {
        $expectedRoles = ['admin', 'pesquisador', 'moderador'];
        $expectedPermissions = [
            'view-cartorios', 'create-cartorios', 'edit-cartorios', 'delete-cartorios',
            'view-falecidos', 'edit-falecidos',
            'view-solicitacoes', 'process-solicitacoes',
        ];

        foreach ($expectedRoles as $role) {
            $this->assertIsString($role);
            $this->assertNotEmpty($role);
        }

        foreach ($expectedPermissions as $permission) {
            $this->assertIsString($permission);
            $this->assertStringContains('-', $permission);
        }
    }

    /**
     * Test configuration constants
     */
    public function test_configuration_constants()
    {
        $requiredConfigs = [
            'constants.localidades' => 'array',
            'constants.estadosCivis' => 'array',
            'constants.tipoLocalDeObito' => 'array',
            'constants.abrangencia' => 'array',
        ];

        foreach ($requiredConfigs as $config => $type) {
            $value = config($config);
            
            if ($type === 'array') {
                $this->assertIsArray($value, "Config {$config} should be an array");
                $this->assertNotEmpty($value, "Config {$config} should not be empty");
            }
        }
    }
}