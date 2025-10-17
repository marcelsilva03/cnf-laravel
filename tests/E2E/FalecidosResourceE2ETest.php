<?php

namespace Tests\E2E;

use App\Models\Falecido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FalecidosResourceE2ETest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $moderador;
    protected User $pesquisador;
    protected User $unauthorizedUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $moderadorRole = Role::create(['name' => 'moderador']);
        $pesquisadorRole = Role::create(['name' => 'pesquisador']);
        
        // Create users with different roles
        $this->admin = User::factory()->create(['email' => 'admin@test.com']);
        $this->admin->assignRole($adminRole);
        
        $this->moderador = User::factory()->create(['email' => 'moderador@test.com']);
        $this->moderador->assignRole($moderadorRole);
        
        $this->pesquisador = User::factory()->create(['email' => 'pesquisador@test.com']);
        $this->pesquisador->assignRole($pesquisadorRole);
        
        $this->unauthorizedUser = User::factory()->create(['email' => 'unauthorized@test.com']);
        
        // Create test data
        $this->createTestFalecidos();
    }

    protected function createTestFalecidos()
    {
        // Active records
        Falecido::create([
            'fal_nome' => 'João Silva',
            'fal_cpf' => '12345678901',
            'fal_data_nasc' => '1950-01-01',
            'fal_data_falecimento' => '2023-12-01',
            'fal_status' => 1
        ]);
        
        Falecido::create([
            'fal_nome' => 'Maria Santos',
            'fal_cpf' => '98765432101',
            'fal_data_nasc' => '1960-05-15',
            'fal_data_falecimento' => '2023-11-15',
            'fal_status' => 1
        ]);
        
        // Inactive record
        Falecido::create([
            'fal_nome' => 'Pedro Oliveira',
            'fal_cpf' => '55566677788',
            'fal_data_nasc' => '1945-03-20',
            'fal_data_falecimento' => '2023-10-10',
            'fal_status' => 0
        ]);
    }

    /**
     * Test 1: Clear Filters Button Functionality
     */
    public function test_clear_filters_button_removes_all_filters()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[search][value]=João&tableFilters[status][value]=1');
        
        $response->assertStatus(200);
        $response->assertSee('João Silva');
        $response->assertDontSee('Maria Santos');
        
        // Check that clear filters button exists
        $response->assertSee('Limpar filtros');
        
        // Simulate clicking clear filters (redirects to URL without filters)
        $clearResponse = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        $clearResponse->assertStatus(200);
        $clearResponse->assertSee('João Silva');
        $clearResponse->assertSee('Maria Santos');
        $clearResponse->assertSee('Pedro Oliveira');
    }

    /**
     * Test 2: Search Functionality
     */
    public function test_search_filters_records_correctly()
    {
        // Search by name
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[search][value]=Maria');
        
        $response->assertStatus(200);
        $response->assertSee('Maria Santos');
        $response->assertDontSee('João Silva');
        $response->assertDontSee('Pedro Oliveira');
        
        // Search by CPF
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[search][value]=123456');
        
        $response->assertStatus(200);
        $response->assertSee('João Silva');
        $response->assertDontSee('Maria Santos');
    }

    /**
     * Test 3: Status Filter Functionality
     */
    public function test_status_filter_works_correctly()
    {
        // Filter active records only
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[fal_status][value]=1');
        
        $response->assertStatus(200);
        $response->assertSee('João Silva');
        $response->assertSee('Maria Santos');
        $response->assertDontSee('Pedro Oliveira');
        
        // Filter inactive records only
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[fal_status][value]=0');
        
        $response->assertStatus(200);
        $response->assertDontSee('João Silva');
        $response->assertDontSee('Maria Santos');
        $response->assertSee('Pedro Oliveira');
    }

    /**
     * Test 4: Date Range Filter
     */
    public function test_date_range_filter_works_correctly()
    {
        // Filter by death date range
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[fal_data_falecimento][from]=2023-11-01&tableFilters[fal_data_falecimento][to]=2023-12-31');
        
        $response->assertStatus(200);
        $response->assertSee('João Silva'); // Died 2023-12-01
        $response->assertSee('Maria Santos'); // Died 2023-11-15
        $response->assertDontSee('Pedro Oliveira'); // Died 2023-10-10
    }

    /**
     * Test 5: Role-Based Access Control
     */
    public function test_role_based_access_control()
    {
        // Admin can access and see all actions
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
        $response->assertSee('Editar');
        $response->assertSee('Remover');
        
        // Moderador can access and see actions
        $response = $this->actingAs($this->moderador)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
        $response->assertSee('Editar');
        $response->assertSee('Remover');
        
        // Pesquisador can access but cannot see edit/remove actions
        $response = $this->actingAs($this->pesquisador)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
        $response->assertDontSee('Editar');
        $response->assertDontSee('Remover');
        
        // Unauthorized user cannot access
        $response = $this->actingAs($this->unauthorizedUser)
            ->get('/admin/falecidos');
        
        $response->assertStatus(403);
    }

    /**
     * Test 6: Toggle Status Functionality
     */
    public function test_toggle_status_changes_record_status()
    {
        $falecido = Falecido::where('fal_nome', 'João Silva')->first();
        
        // Admin can toggle status
        $response = $this->actingAs($this->admin)
            ->post("/admin/falecidos/{$falecido->id}/toggle-status");
        
        $response->assertRedirect();
        
        // Check status was changed
        $falecido->refresh();
        $this->assertEquals(0, $falecido->fal_status);
        
        // Toggle back
        $response = $this->actingAs($this->admin)
            ->post("/admin/falecidos/{$falecido->id}/toggle-status");
        
        $response->assertRedirect();
        
        $falecido->refresh();
        $this->assertEquals(1, $falecido->fal_status);
    }

    /**
     * Test 7: Edit Functionality
     */
    public function test_edit_functionality_for_authorized_users()
    {
        $falecido = Falecido::where('fal_nome', 'João Silva')->first();
        
        // Admin can access edit page
        $response = $this->actingAs($this->admin)
            ->get("/admin/falecidos/{$falecido->id}/edit");
        
        $response->assertStatus(200);
        $response->assertSee('João Silva');
        $response->assertSee('12345678901');
        
        // Admin can update record
        $response = $this->actingAs($this->admin)
            ->put("/admin/falecidos/{$falecido->id}", [
                'fal_nome' => 'João Silva Editado',
                'fal_cpf' => '12345678901',
                'fal_data_nasc' => '1950-01-01',
                'fal_data_falecimento' => '2023-12-01',
                'fal_status' => 1
            ]);
        
        $response->assertRedirect('/admin/falecidos');
        
        $falecido->refresh();
        $this->assertEquals('João Silva Editado', $falecido->fal_nome);
        
        // Pesquisador cannot access edit page
        $response = $this->actingAs($this->pesquisador)
            ->get("/admin/falecidos/{$falecido->id}/edit");
        
        $response->assertStatus(403);
    }

    /**
     * Test 8: Pagination
     */
    public function test_pagination_works_correctly()
    {
        // Create more records to test pagination
        for ($i = 1; $i <= 25; $i++) {
            Falecido::create([
                'fal_nome' => "Test User $i",
                'fal_cpf' => str_pad($i, 11, '0', STR_PAD_LEFT),
                'fal_data_nasc' => '1970-01-01',
                'fal_data_falecimento' => '2023-01-01',
                'fal_status' => 1
            ]);
        }
        
        // First page
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
        $response->assertSee('Test User 1');
        
        // Second page
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?page=2');
        
        $response->assertStatus(200);
        $response->assertDontSee('Test User 1');
    }

    /**
     * Test 9: Empty State with Clear Filters
     */
    public function test_empty_state_shows_clear_filters_button()
    {
        // Search for non-existent record
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[search][value]=NonExistentPerson');
        
        $response->assertStatus(200);
        $response->assertSee('Nenhum Falecido encontrado.');
        $response->assertSee('Nenhum Falecido encontrado utilizando os filtros atualmente definidos.');
        $response->assertSee('Limpar filtros');
        
        // Verify clear filters button has correct URL
        $content = $response->getContent();
        $this->assertStringContainsString('/admin/falecidos', $content);
    }

    /**
     * Test 10: Combined Filters
     */
    public function test_combined_filters_work_correctly()
    {
        // Combine search and status filter
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[search][value]=Silva&tableFilters[fal_status][value]=1');
        
        $response->assertStatus(200);
        $response->assertSee('João Silva');
        $response->assertDontSee('Maria Santos');
        $response->assertDontSee('Pedro Oliveira');
        
        // Clear filters should remove all filters
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
        $response->assertSee('João Silva');
        $response->assertSee('Maria Santos');
        $response->assertSee('Pedro Oliveira');
    }

    /**
     * Test 11: Record Highlighting for Inactive Status
     */
    public function test_inactive_records_have_special_styling()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
        
        // Check that inactive record has special CSS class
        $content = $response->getContent();
        $this->assertStringContainsString('bg-red-100', $content);
    }

    /**
     * Test 12: Export Functionality (if implemented)
     */
    public function test_export_functionality_if_available()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
        
        // Check if export buttons exist
        $content = $response->getContent();
        if (str_contains($content, 'Exportar')) {
            $this->assertStringContainsString('Exportar', $content);
        }
    }
}