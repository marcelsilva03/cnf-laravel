<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Falecido;
use App\Models\Solicitacao;
use App\Models\Cartorio;
use App\Services\LocalidadesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SolicitacaoPesquisaWorkflowEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test scenario: Multiple falecidos with same name
     */
    public function test_search_returns_multiple_results_with_same_name()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Create multiple falecidos with same name but different details
        $falecidos = [];
        for ($i = 1; $i <= 3; $i++) {
            $falecidos[] = Falecido::create([
                'fal_uuid' => \Str::uuid(),
                'fal_nome' => 'João Silva',
                'fal_cpf' => "1234567890{$i}",
                'fal_data_falecimento' => Carbon::now()->subDays($i * 30)->format('Y-m-d'),
                'fal_uf' => 'SP',
                'fal_cidade' => 'São Paulo',
                'fal_nome_mae' => "Maria Silva {$i}",
                'fal_status' => 1
            ]);
        }
        
        $response = $this->post('/resultados', [
            'nome' => 'João Silva',
            'uf' => 'SP'
        ]);
        
        $response->assertStatus(200);
        foreach ($falecidos as $falecido) {
            $response->assertSee($falecido->fal_nome_mae);
        }
    }

    /**
     * Test scenario: Payment timeout and retry
     */
    public function test_payment_timeout_and_retry_workflow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $solicitacao = Solicitacao::factory()->create([
            'user_id' => $user->id,
            'sol_status' => Solicitacao::STATUS['AGUARDANDO_PAGAMENTO'],
            'created_at' => Carbon::now()->subHours(3) // 3 hours ago
        ]);
        
        // Check if payment expired
        $this->assertTrue($solicitacao->isPaymentExpired());
        
        // Retry payment
        $response = $this->get("/solicitacoes/{$solicitacao->sol_id}/retry-payment");
        $response->assertRedirect('/pagamento-pesquisa');
        
        $solicitacao->refresh();
        $this->assertEquals(Solicitacao::STATUS['AGUARDANDO_PAGAMENTO'], $solicitacao->sol_status);
        $this->assertGreaterThan(Carbon::now()->subMinute(), $solicitacao->updated_at);
    }

    /**
     * Test scenario: Cartorio not found in system
     */
    public function test_cartorio_not_in_system_manual_entry()
    {
        $pesquisador = User::factory()->create();
        $pesquisador->assignRole('pesquisador');
        $this->actingAs($pesquisador);
        
        $solicitacao = Solicitacao::factory()->create([
            'sol_status' => Solicitacao::STATUS['PAGO'],
            'sol_cartorio_manual' => 'Cartório não cadastrado - 5º Ofício de Registro Civil',
            'sol_cartorio_telefone' => '(11) 5555-5555',
            'sol_cartorio_email' => 'contato@cartorionovo.com.br'
        ]);
        
        $response = $this->get("/admin/solicitacoes/{$solicitacao->sol_id}");
        $response->assertStatus(200);
        $response->assertSee('Cartório não cadastrado');
        $response->assertSee('Entrada manual de dados do cartório');
    }

    /**
     * Test scenario: Invalid CPF/RG formats
     */
    public function test_search_with_invalid_document_formats()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Test with invalid CPF
        $response = $this->post('/busca-avancada', [
            'nome_fal' => 'Test User',
            'cpf' => 'invalid-cpf-123',
            'estado_obito' => 'SP'
        ]);
        
        $response->assertSessionHasErrors(['cpf']);
        
        // Test with valid CPF format
        $response = $this->post('/busca-avancada', [
            'nome_fal' => 'Test User',
            'cpf' => '123.456.789-01',
            'estado_obito' => 'SP'
        ]);
        
        $response->assertSessionDoesntHaveErrors(['cpf']);
    }

    /**
     * Test scenario: Localidades service integration
     */
    public function test_dynamic_city_loading_based_on_state()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Test API endpoint for cities
        $response = $this->get('/api/localidades/SP');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['nome']
        ]);
        
        $cities = $response->json();
        $this->assertContains(['nome' => 'São Paulo'], $cities);
        
        // Test with different state
        $response = $this->get('/api/localidades/RJ');
        $response->assertStatus(200);
        $cities = $response->json();
        $this->assertContains(['nome' => 'Rio de Janeiro'], $cities);
    }

    /**
     * Test scenario: Concurrent payment attempts
     */
    public function test_prevent_duplicate_payment_processing()
    {
        $user = User::factory()->create();
        $solicitacao = Solicitacao::factory()->create([
            'user_id' => $user->id,
            'sol_status' => Solicitacao::STATUS['AGUARDANDO_PAGAMENTO']
        ]);
        
        // Simulate two concurrent payment confirmations
        $paymentData = [
            'transaction_id' => 'PAY-123',
            'status' => 'approved',
            'reference' => $solicitacao->sol_id
        ];
        
        // First payment
        $response1 = $this->post('/api/pagamentos/efi/notificacao-cartao', $paymentData);
        $response1->assertStatus(200);
        
        $solicitacao->refresh();
        $this->assertEquals(Solicitacao::STATUS['PAGO'], $solicitacao->sol_status);
        
        // Second payment attempt (should be rejected)
        $paymentData['transaction_id'] = 'PAY-456';
        $response2 = $this->post('/api/pagamentos/efi/notificacao-cartao', $paymentData);
        $response2->assertStatus(422); // Unprocessable entity
        $response2->assertJson(['error' => 'Payment already processed']);
    }

    /**
     * Test scenario: Date validation for death date
     */
    public function test_death_date_cannot_be_future()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $futureDate = Carbon::now()->addDays(5)->format('Y-m-d');
        
        $response = $this->post('/comunicarobito', [
            'nome_fal' => 'Test Person',
            'data_obito' => $futureDate,
            'email_sol' => $user->email
        ]);
        
        $response->assertSessionHasErrors(['data_obito']);
    }

    /**
     * Test scenario: Search with special characters in name
     */
    public function test_search_handles_special_characters()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Create falecido with special characters
        $falecido = Falecido::create([
            'fal_uuid' => \Str::uuid(),
            'fal_nome' => "José D'Ávila São João",
            'fal_cpf' => '12345678901',
            'fal_uf' => 'SP',
            'fal_status' => 1
        ]);
        
        // Search with exact name
        $response = $this->post('/resultados', [
            'nome' => "José D'Ávila São João",
            'uf' => 'SP'
        ]);
        
        $response->assertStatus(200);
        $response->assertSee($falecido->fal_nome);
        
        // Search with partial name
        $response = $this->post('/resultados', [
            'nome' => "D'Ávila",
            'uf' => 'SP'
        ]);
        
        $response->assertStatus(200);
        $response->assertSee($falecido->fal_nome);
    }

    /**
     * Test scenario: Bulk status update by pesquisador
     */
    public function test_pesquisador_bulk_update_solicitacoes()
    {
        $pesquisador = User::factory()->create();
        $pesquisador->assignRole('pesquisador');
        $this->actingAs($pesquisador);
        
        // Create multiple solicitações
        $solicitacoes = Solicitacao::factory()->count(5)->create([
            'sol_status' => Solicitacao::STATUS['PAGO']
        ]);
        
        $ids = $solicitacoes->pluck('sol_id')->toArray();
        
        $response = $this->post('/admin/solicitacoes/bulk-update', [
            'ids' => $ids,
            'status' => Solicitacao::STATUS['EM_ANDAMENTO'],
            'observacao' => 'Iniciando processamento em lote'
        ]);
        
        $response->assertRedirect();
        
        foreach ($solicitacoes as $solicitacao) {
            $solicitacao->refresh();
            $this->assertEquals(Solicitacao::STATUS['EM_ANDAMENTO'], $solicitacao->sol_status);
        }
    }

    /**
     * Test scenario: Email notification preferences
     */
    public function test_user_email_notification_preferences()
    {
        $user = User::factory()->create([
            'email_notifications' => false
        ]);
        $this->actingAs($user);
        
        Mail::fake();
        
        $solicitacao = Solicitacao::factory()->create([
            'user_id' => $user->id,
            'sol_email_sol' => $user->email,
            'sol_status' => Solicitacao::STATUS['FINALIZADO']
        ]);
        
        // Should not send email if user disabled notifications
        Mail::assertNothingSent();
        
        // Enable notifications
        $user->email_notifications = true;
        $user->save();
        
        // Trigger notification again
        $solicitacao->notifyCompletion();
        
        Mail::assertSent(function ($mail) use ($user) {
            return $mail->to[0]['address'] === $user->email;
        });
    }
}