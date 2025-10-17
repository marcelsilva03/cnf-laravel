<?php

namespace Tests\Feature;

use App\Models\Plano;
use App\Models\User;
use App\Rules\PlanoFaixaValidation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PlanoValidationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar roles necessárias
        Role::create(['name' => 'admin']);
        
        // Criar usuário admin para os testes
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');
        
        // Criar alguns planos base para testes
        $this->createBasePlanos();
    }

    private function createBasePlanos(): void
    {
        Plano::create([
            'faixa_inicial' => 0,
            'faixa_final' => 9999,
            'preco_por_consulta' => 0.5591,
            'ativo' => true,
        ]);

        Plano::create([
            'faixa_inicial' => 10000,
            'faixa_final' => 19999,
            'preco_por_consulta' => 0.5649,
            'ativo' => true,
        ]);

        Plano::create([
            'faixa_inicial' => 20000,
            'faixa_final' => null, // Faixa ilimitada
            'preco_por_consulta' => 0.1779,
            'ativo' => true,
        ]);
    }

    /** @test */
    public function regra_a_faixa_inicial_nao_pode_ser_maior_que_faixa_final()
    {
        $validator = Validator::make([
            'faixa_inicial' => 15000,
            'faixa_final' => 10000,
        ], [
            'faixa_inicial' => [new PlanoFaixaValidation('faixa_inicial')],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('não pode ser maior que a faixa final', $validator->errors()->first('faixa_inicial'));
    }

    /** @test */
    public function regra_b_faixa_inicial_nao_pode_ser_negativa()
    {
        $validator = Validator::make([
            'faixa_inicial' => -1000,
            'faixa_final' => 5000,
        ], [
            'faixa_inicial' => [new PlanoFaixaValidation('faixa_inicial')],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('não pode ser negativa', $validator->errors()->first('faixa_inicial'));
    }

    /** @test */
    public function regra_b_faixa_final_nao_pode_ser_negativa()
    {
        $validator = Validator::make([
            'faixa_inicial' => 1000,
            'faixa_final' => -500,
        ], [
            'faixa_final' => [new PlanoFaixaValidation('faixa_final')],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('não pode ser negativa', $validator->errors()->first('faixa_final'));
    }

    /** @test */
    public function regra_c_faixa_inicial_duplicada_deve_falhar()
    {
        $validator = Validator::make([
            'faixa_inicial' => 0, // Já existe um plano com faixa inicial 0
            'faixa_final' => 5000,
        ], [
            'faixa_inicial' => [new PlanoFaixaValidation('faixa_inicial')],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('Já existe um plano cadastrado', $validator->errors()->first('faixa_inicial'));
    }

    /** @test */
    public function regra_c_faixa_final_duplicada_deve_falhar()
    {
        $validator = Validator::make([
            'faixa_inicial' => 30000,
            'faixa_final' => 9999, // Já existe um plano com faixa final 9999
        ], [
            'faixa_final' => [new PlanoFaixaValidation('faixa_final')],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('Já existe um plano cadastrado', $validator->errors()->first('faixa_final'));
    }

    /** @test */
    public function regra_d_faixa_inicial_dentro_de_plano_existente_deve_falhar()
    {
        $validator = Validator::make([
            'faixa_inicial' => 5000, // Está dentro da faixa 0-9999
            'faixa_final' => 25000,
        ], [
            'faixa_inicial' => [new PlanoFaixaValidation('faixa_inicial')],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('conflita com o plano existente', $validator->errors()->first('faixa_inicial'));
    }

    /** @test */
    public function regra_d_faixa_final_dentro_de_plano_existente_deve_falhar()
    {
        $validator = Validator::make([
            'faixa_inicial' => 25000,
            'faixa_final' => 15000, // Está dentro da faixa 10000-19999
        ], [
            'faixa_final' => [new PlanoFaixaValidation('faixa_final')],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('conflita com o plano existente', $validator->errors()->first('faixa_final'));
    }

    /** @test */
    public function regra_d_sobreposicao_com_faixa_ilimitada_deve_falhar()
    {
        $validator = Validator::make([
            'faixa_inicial' => 25000, // Conflita com plano que começa em 20000 e vai até infinito
            'faixa_final' => 30000,
        ], [
            'faixa_inicial' => [new PlanoFaixaValidation('faixa_inicial')],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('conflita com o plano existente', $validator->errors()->first('faixa_inicial'));
    }

    /** @test */
    public function edicao_de_plano_deve_ignorar_o_proprio_plano()
    {
        $plano = Plano::first();
        
        $validator = Validator::make([
            'faixa_inicial' => $plano->faixa_inicial, // Mesmo valor do plano sendo editado
            'faixa_final' => $plano->faixa_final,
        ], [
            'faixa_inicial' => [new PlanoFaixaValidation('faixa_inicial', $plano->id)],
        ]);

        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function faixas_validas_nao_devem_falhar()
    {
        $validator = Validator::make([
            'faixa_inicial' => 50000, // Faixa válida que não conflita
            'faixa_final' => 59999,
        ], [
            'faixa_inicial' => [new PlanoFaixaValidation('faixa_inicial')],
            'faixa_final' => [new PlanoFaixaValidation('faixa_final')],
        ]);

        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function faixa_final_nula_deve_ser_permitida()
    {
        $validator = Validator::make([
            'faixa_inicial' => 100000,
            'faixa_final' => null,
        ], [
            'faixa_inicial' => [new PlanoFaixaValidation('faixa_inicial')],
            'faixa_final' => [new PlanoFaixaValidation('faixa_final')],
        ]);

        $this->assertTrue($validator->fails()); // Deve falhar porque já existe uma faixa ilimitada a partir de 20000
    }

    /** @test */
    public function valores_zero_devem_ser_permitidos()
    {
        // Remover plano que começa em 0 para testar
        Plano::where('faixa_inicial', 0)->delete();
        
        $validator = Validator::make([
            'faixa_inicial' => 0,
            'faixa_final' => 5000,
        ], [
            'faixa_inicial' => [new PlanoFaixaValidation('faixa_inicial')],
            'faixa_final' => [new PlanoFaixaValidation('faixa_final')],
        ]);

        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function teste_integracao_criacao_via_filament()
    {
        $this->actingAs($this->adminUser);

        // Tentar criar plano com faixa inválida
        $response = $this->post(route('filament.admin.resources.planos.store'), [
            'faixa_inicial' => 5000, // Conflita com faixa existente 0-9999
            'faixa_final' => 8000,
            'preco_por_consulta' => 0.50,
            'ativo' => true,
        ]);

        // Deve retornar erro de validação
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function teste_integracao_edicao_via_filament()
    {
        $this->actingAs($this->adminUser);
        
        $plano = Plano::first();

        // Tentar editar para valores válidos
        $response = $this->put(route('filament.admin.resources.planos.update', $plano), [
            'faixa_inicial' => $plano->faixa_inicial,
            'faixa_final' => $plano->faixa_final,
            'preco_por_consulta' => 0.60, // Apenas mudança de preço
            'ativo' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('planos', [
            'id' => $plano->id,
            'preco_por_consulta' => 0.60,
        ]);
    }
} 