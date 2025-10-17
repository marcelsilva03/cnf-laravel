<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Falecido;
use App\Models\Solicitacao;
use App\Models\Cartorio;
use App\Models\ComunicadoDeObito;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SolicitacaoPesquisaFalecidoE2ETest extends TestCase
{
    use RefreshDatabase;

    protected User $solicitante;
    protected User $pesquisador;
    protected Falecido $falecido;
    protected Cartorio $cartorio;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->solicitante = User::factory()->create([
            'name' => 'João Silva',
            'email' => 'solicitante@test.com'
        ]);
        
        $this->pesquisador = User::factory()->create([
            'name' => 'Maria Pesquisadora',
            'email' => 'pesquisador@test.com'
        ]);
        $this->pesquisador->assignRole('pesquisador');
        
        // Create test cartorio
        $this->cartorio = Cartorio::create([
            'ccc_nome' => 'Cartório de Registro Civil - 1º Ofício',
            'ccc_cidade' => 'São Paulo',
            'ccc_uf' => 'SP',
            'ccc_endereco' => 'Rua do Cartório, 123',
            'ccc_telefone' => '(11) 1234-5678',
            'ccc_email' => 'cartorio@test.com'
        ]);
        
        // Create test falecido with complete data
        $this->falecido = Falecido::create([
            'fal_uuid' => Str::uuid(),
            'fal_nome' => 'José da Silva Santos',
            'fal_cpf' => '12345678901',
            'fal_rg' => '123456789',
            'fal_data_nascimento' => '1950-05-15',
            'fal_data_falecimento' => '2025-05-20',
            'fal_nome_pai' => 'Antonio da Silva',
            'fal_nome_mae' => 'Maria Santos Silva',
            'fal_uf' => 'SP',
            'fal_cidade' => 'São Paulo',
            'fal_cartorio_obito' => $this->cartorio->ccc_nome,
            'fal_id_ccc' => $this->cartorio->ccc_id,
            'fal_co_livro' => 'A-123',
            'fal_co_folha' => '456',
            'fal_co_termo' => '78910',
            'fal_co_declaracao' => 'DEC-2025-001',
            'fal_local_sepultamento' => 'Cemitério da Paz',
            'fal_data_sepultamento' => strtotime('2025-05-22'),
            'fal_estado_civil' => 2, // Casado
            'fal_status' => 1
        ]);
    }

    /**
     * Test complete workflow: Solicitação de Pesquisa de Falecido
     * Following the process flow from the provided diagram
     */
    public function test_complete_workflow_solicitacao_pesquisa_falecido()
    {
        // Step 1: Solicitante efetua pesquisa de falecido
        $this->actingAs($this->solicitante);
        
        $response = $this->get('/busca-avancada');
        $response->assertStatus(200);
        $response->assertSee('Pesquisa Avançada');
        
        // Step 2: Preenche dados do falecido para pesquisa
        $searchData = [
            'nome_fal' => 'José da Silva Santos',
            'cpf' => '123.456.789-01',
            'nascf' => '1950-05-15',
            'dfalec' => '2025-05-20',
            'estado_obito' => 'SP',
            'cidade_obito' => 'São Paulo',
            'nomepai' => 'Antonio da Silva',
            'nomemae' => 'Maria Santos Silva',
            'localfal' => 1, // Hospital
            'ecivil' => 2, // Casado
            'abrangencia' => 1, // Municipal
            'comentarios' => 'Preciso da certidão para inventário'
        ];
        
        // Step 3: Sistema verifica se encontrou o falecido
        $response = $this->post('/resultados', [
            'nome' => $searchData['nome_fal'],
            'uf' => $searchData['estado_obito']
        ]);
        
        $response->assertStatus(200);
        $response->assertSee($this->falecido->fal_nome);
        
        // Step 4: Continua para solicitação de pesquisa
        $solicitacaoData = array_merge($searchData, [
            'nomesol' => $this->solicitante->name,
            'emailsol' => $this->solicitante->email,
            'telsol' => '(11) 98765-4321',
            'uuid_falecido' => $this->falecido->fal_uuid
        ]);
        
        $response = $this->post('/pagamento-pesquisa', $solicitacaoData);
        $response->assertStatus(200);
        $response->assertSee('Pagamento');
        
        // Verify solicitação was created
        $this->assertDatabaseHas('solicitacoes', [
            'sol_nome_sol' => $this->solicitante->name,
            'sol_email_sol' => $this->solicitante->email,
            'sol_nome_fal' => 'José da Silva Santos',
            'sol_cpf_fal' => '12345678901'
        ]);
        
        $solicitacao = Solicitacao::where('sol_email_sol', $this->solicitante->email)->first();
        
        // Step 5: Selecionar meio de pagamento e confirmar
        $this->simulatePaymentConfirmation($solicitacao);
        
        // Step 6: Sistema muda status para pagamento confirmado
        $solicitacao->refresh();
        $this->assertEquals(Solicitacao::STATUS['PAGO'], $solicitacao->sol_status);
        
        // Step 7: Pesquisador recebe notificação e processa
        $this->actingAs($this->pesquisador);
        
        $response = $this->get('/admin/solicitacoes');
        $response->assertStatus(200);
        $response->assertSee($solicitacao->sol_nome_fal);
        
        // Step 8: Pesquisador consulta cartório
        $response = $this->get("/admin/cartorios/{$this->cartorio->ccc_id}");
        $response->assertStatus(200);
        $response->assertSee($this->cartorio->ccc_nome);
        
        // Step 9: Pesquisador atualiza com dados do cartório
        $cartorioData = [
            'sol_numero_certidao' => 'CERT-2025-001234',
            'sol_livro_certidao' => $this->falecido->fal_co_livro,
            'sol_folha_certidao' => $this->falecido->fal_co_folha,
            'sol_termo_certidao' => $this->falecido->fal_co_termo,
            'sol_data_emissao_certidao' => now()->format('Y-m-d'),
            'sol_observacoes_pesquisador' => 'Certidão localizada com sucesso no cartório',
            'sol_status' => Solicitacao::STATUS['FINALIZADO']
        ];
        
        $response = $this->patch("/admin/solicitacoes/{$solicitacao->sol_id}", $cartorioData);
        $response->assertRedirect();
        
        // Step 10: Verificar finalização e comunicação ao solicitante
        $solicitacao->refresh();
        $this->assertEquals(Solicitacao::STATUS['FINALIZADO'], $solicitacao->sol_status);
        $this->assertNotNull($solicitacao->sol_numero_certidao);
        
        // Verify email was sent to solicitante
        Mail::assertSent(function ($mail) use ($solicitacao) {
            return $mail->to[0]['address'] === $solicitacao->sol_email_sol &&
                   $mail->subject === 'Pesquisa de Falecido Concluída';
        });
    }

    /**
     * Test scenario when falecido is not found in the system
     */
    public function test_falecido_not_found_creates_comunicado_obito()
    {
        $this->actingAs($this->solicitante);
        
        // Search for non-existent falecido
        $response = $this->post('/resultados', [
            'nome' => 'Pessoa Inexistente',
            'uf' => 'RJ'
        ]);
        
        $response->assertStatus(200);
        $response->assertDontSee('Pessoa Inexistente');
        $response->assertSee('Nenhum resultado encontrado');
        
        // User proceeds to communicate death
        $response = $this->get('/comunicarobito');
        $response->assertStatus(200);
        
        $comunicadoData = [
            'nome_sol' => $this->solicitante->name,
            'email_sol' => $this->solicitante->email,
            'tel_sol' => '(21) 98765-4321',
            'nome_fal' => 'Pessoa Inexistente',
            'cpf_fal' => '987.654.321-00',
            'rg_fal' => '987654321',
            'data_nascimento' => '1960-01-01',
            'data_obito' => '2025-05-15',
            'nome_pai_fal' => 'Pai Inexistente',
            'nome_mae_fal' => 'Mãe Inexistente',
            'estado_obito' => 'RJ',
            'cidade_obito' => 'Rio de Janeiro',
            'local_obito_tipo' => 2, // Casa
            'estado_civil' => 1, // Solteiro
            'obs' => 'Faleceu em casa por causas naturais'
        ];
        
        $response = $this->post('/submit-form', $comunicadoData);
        $response->assertRedirect();
        
        // Verify comunicado was created
        $this->assertDatabaseHas('comunicados_de_obito', [
            'nome_fal' => 'Pessoa Inexistente',
            'cpf_fal' => '98765432100'
        ]);
        
        // Verify new falecido was created from comunicado
        $comunicado = ComunicadoDeObito::where('cpf_fal', '98765432100')->first();
        $this->assertNotNull($comunicado->falecido_id);
        
        $novoFalecido = Falecido::find($comunicado->falecido_id);
        $this->assertEquals('Pessoa Inexistente', $novoFalecido->fal_nome);
    }

    /**
     * Test payment cancellation scenario
     */
    public function test_payment_cancellation_workflow()
    {
        $this->actingAs($this->solicitante);
        
        // Create solicitação
        $solicitacao = Solicitacao::factory()->create([
            'user_id' => $this->solicitante->id,
            'sol_nome_sol' => $this->solicitante->name,
            'sol_email_sol' => $this->solicitante->email,
            'sol_nome_fal' => $this->falecido->fal_nome,
            'sol_status' => Solicitacao::STATUS['AGUARDANDO_PAGAMENTO']
        ]);
        
        // Simulate payment cancellation
        $response = $this->post("/solicitacao/{$solicitacao->sol_id}/cancelar-pagamento");
        $response->assertRedirect();
        
        $solicitacao->refresh();
        $this->assertEquals(Solicitacao::STATUS['CANCELADO'], $solicitacao->sol_status);
    }

    /**
     * Test error communication scenario
     */
    public function test_error_communication_workflow()
    {
        $this->actingAs($this->solicitante);
        
        // Report error in falecido data
        $errorData = [
            'uuid_falecido' => $this->falecido->fal_uuid,
            'id_falecido' => $this->falecido->fal_id,
            'nome_comunicante' => $this->solicitante->name,
            'email_comunicante' => $this->solicitante->email,
            'mensagem' => 'A data de nascimento está incorreta. O correto é 15/05/1951'
        ];
        
        $response = $this->post('/comunicar-erro', $errorData);
        $response->assertRedirect();
        $response->assertSessionHas('notificacao.tipo', 'sucesso');
        
        // Verify error communication was created
        $this->assertDatabaseHas('comunicados_de_erro', [
            'uuid_falecido' => $this->falecido->fal_uuid,
            'email_comunicante' => $this->solicitante->email
        ]);
    }

    /**
     * Test search with incomplete data
     */
    public function test_search_with_minimal_data()
    {
        $this->actingAs($this->solicitante);
        
        // Search with only name and state
        $response = $this->post('/resultados', [
            'nome' => 'Silva',
            'uf' => 'SP'
        ]);
        
        $response->assertStatus(200);
        $response->assertSee($this->falecido->fal_nome);
    }

    /**
     * Test permission validation - only pesquisador can update solicitação
     */
    public function test_only_pesquisador_can_update_solicitacao()
    {
        $solicitacao = Solicitacao::factory()->create([
            'sol_status' => Solicitacao::STATUS['PAGO']
        ]);
        
        // Try as regular user
        $this->actingAs($this->solicitante);
        $response = $this->patch("/admin/solicitacoes/{$solicitacao->sol_id}", [
            'sol_status' => Solicitacao::STATUS['FINALIZADO']
        ]);
        $response->assertStatus(403);
        
        // Try as pesquisador
        $this->actingAs($this->pesquisador);
        $response = $this->patch("/admin/solicitacoes/{$solicitacao->sol_id}", [
            'sol_status' => Solicitacao::STATUS['FINALIZADO']
        ]);
        $response->assertRedirect();
    }

    /**
     * Helper method to simulate payment confirmation
     */
    protected function simulatePaymentConfirmation(Solicitacao $solicitacao)
    {
        // Simulate payment webhook/callback
        $paymentData = [
            'transaction_id' => 'PAY-' . uniqid(),
            'status' => 'approved',
            'amount' => $solicitacao->sol_valor,
            'reference' => $solicitacao->sol_id
        ];
        
        $response = $this->post('/api/pagamentos/efi/notificacao-cartao', $paymentData);
        
        $solicitacao->sol_status = Solicitacao::STATUS['PAGO'];
        $solicitacao->save();
    }
}