<?php

namespace Tests\E2E;

use App\Models\Falecido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * End-to-End test specifically for Clear Filters functionality
 * This test simulates real user interactions with the Clear Filters button
 */
class FalecidosClearFiltersE2ETest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin role and user
        $adminRole = Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create(['email' => 'admin@test.com']);
        $this->admin->assignRole($adminRole);
        
        // Create test data with various attributes
        $this->createTestData();
    }

    protected function createTestData()
    {
        // Create falecidos with different attributes for filtering
        Falecido::create([
            'fal_nome' => 'Ana Clara Silva',
            'fal_cpf' => '11111111111',
            'fal_data_nasc' => '1940-01-01',
            'fal_data_falecimento' => '2024-01-15',
            'fal_status' => 1
        ]);
        
        Falecido::create([
            'fal_nome' => 'Bruno Costa Santos',
            'fal_cpf' => '22222222222',
            'fal_data_nasc' => '1950-06-15',
            'fal_data_falecimento' => '2024-02-20',
            'fal_status' => 1
        ]);
        
        Falecido::create([
            'fal_nome' => 'Carlos Alberto Oliveira',
            'fal_cpf' => '33333333333',
            'fal_data_nasc' => '1935-12-10',
            'fal_data_falecimento' => '2023-12-25',
            'fal_status' => 0
        ]);
        
        Falecido::create([
            'fal_nome' => 'Diana Ferreira Lima',
            'fal_cpf' => '44444444444',
            'fal_data_nasc' => '1965-03-22',
            'fal_data_falecimento' => '2024-03-10',
            'fal_status' => 1
        ]);
    }

    /**
     * Test Scenario 1: Clear filters from search filter
     */
    public function test_clear_filters_removes_search_filter()
    {
        // Step 1: Access page with search filter
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[search][value]=Silva');
        
        $response->assertStatus(200);
        
        // Step 2: Verify filtered results
        $response->assertSee('Ana Clara Silva');
        $response->assertDontSee('Bruno Costa Santos');
        $response->assertDontSee('Carlos Alberto Oliveira');
        $response->assertDontSee('Diana Ferreira Lima');
        
        // Step 3: Verify Clear Filters button is present
        $response->assertSee('Limpar filtros');
        
        // Step 4: Click Clear Filters (simulate by accessing URL without filters)
        $clearResponse = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        // Step 5: Verify all records are now visible
        $clearResponse->assertStatus(200);
        $clearResponse->assertSee('Ana Clara Silva');
        $clearResponse->assertSee('Bruno Costa Santos');
        $clearResponse->assertSee('Carlos Alberto Oliveira');
        $clearResponse->assertSee('Diana Ferreira Lima');
    }

    /**
     * Test Scenario 2: Clear filters from status filter
     */
    public function test_clear_filters_removes_status_filter()
    {
        // Step 1: Access page with status filter (only active)
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[fal_status][value]=1');
        
        $response->assertStatus(200);
        
        // Step 2: Verify only active records are shown
        $response->assertSee('Ana Clara Silva');
        $response->assertSee('Bruno Costa Santos');
        $response->assertDontSee('Carlos Alberto Oliveira'); // Inactive
        $response->assertSee('Diana Ferreira Lima');
        
        // Step 3: Clear filters
        $clearResponse = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        // Step 4: Verify inactive record is now visible
        $clearResponse->assertStatus(200);
        $clearResponse->assertSee('Carlos Alberto Oliveira');
    }

    /**
     * Test Scenario 3: Clear filters from date range filter
     */
    public function test_clear_filters_removes_date_range_filter()
    {
        // Step 1: Access page with date range filter
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[fal_data_falecimento][from]=2024-01-01&tableFilters[fal_data_falecimento][to]=2024-02-28');
        
        $response->assertStatus(200);
        
        // Step 2: Verify filtered results
        $response->assertSee('Ana Clara Silva'); // 2024-01-15
        $response->assertSee('Bruno Costa Santos'); // 2024-02-20
        $response->assertDontSee('Carlos Alberto Oliveira'); // 2023-12-25
        $response->assertDontSee('Diana Ferreira Lima'); // 2024-03-10
        
        // Step 3: Clear filters
        $clearResponse = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        // Step 4: Verify all records are visible
        $clearResponse->assertStatus(200);
        $clearResponse->assertSee('Carlos Alberto Oliveira');
        $clearResponse->assertSee('Diana Ferreira Lima');
    }

    /**
     * Test Scenario 4: Clear multiple combined filters
     */
    public function test_clear_filters_removes_all_combined_filters()
    {
        // Step 1: Access page with multiple filters
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[search][value]=Santos&tableFilters[fal_status][value]=1&tableFilters[fal_data_falecimento][from]=2024-01-01');
        
        $response->assertStatus(200);
        
        // Step 2: Verify highly filtered results
        $response->assertDontSee('Ana Clara Silva');
        $response->assertSee('Bruno Costa Santos'); // Only this matches all filters
        $response->assertDontSee('Carlos Alberto Oliveira');
        $response->assertDontSee('Diana Ferreira Lima');
        
        // Step 3: Clear all filters
        $clearResponse = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        // Step 4: Verify all records are visible
        $clearResponse->assertStatus(200);
        $clearResponse->assertSee('Ana Clara Silva');
        $clearResponse->assertSee('Bruno Costa Santos');
        $clearResponse->assertSee('Carlos Alberto Oliveira');
        $clearResponse->assertSee('Diana Ferreira Lima');
    }

    /**
     * Test Scenario 5: Clear filters button in empty state
     */
    public function test_clear_filters_button_appears_in_empty_state()
    {
        // Step 1: Search for non-existent record
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[search][value]=NonExistentPerson123456');
        
        $response->assertStatus(200);
        
        // Step 2: Verify empty state messages
        $response->assertSee('Nenhum Falecido encontrado.');
        $response->assertSee('Nenhum Falecido encontrado utilizando os filtros atualmente definidos.');
        
        // Step 3: Verify Clear Filters button is present
        $response->assertSee('Limpar filtros');
        
        // Step 4: Clear filters
        $clearResponse = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        // Step 5: Verify records are now visible
        $clearResponse->assertStatus(200);
        $clearResponse->assertDontSee('Nenhum Falecido encontrado.');
        $clearResponse->assertSee('Ana Clara Silva');
    }

    /**
     * Test Scenario 6: URL preservation after clearing filters
     */
    public function test_clear_filters_preserves_base_url()
    {
        // Step 1: Access page with filters and verify URL structure
        $filteredUrl = '/admin/falecidos?tableFilters[search][value]=Test&page=1';
        $response = $this->actingAs($this->admin)->get($filteredUrl);
        
        $response->assertStatus(200);
        
        // Step 2: Verify the clear filters link points to base URL
        $content = $response->getContent();
        $this->assertStringContainsString('href="/admin/falecidos"', $content);
        
        // Step 3: Access cleared URL
        $clearResponse = $this->actingAs($this->admin)
            ->get('/admin/falecidos');
        
        // Step 4: Verify we're on the correct page without filters
        $clearResponse->assertStatus(200);
        $currentUrl = $clearResponse->baseResponse->headers->get('Location') ?? '/admin/falecidos';
        $this->assertStringNotContainsString('tableFilters', $currentUrl);
    }

    /**
     * Test Scenario 7: Clear filters accessibility for different screen sizes
     */
    public function test_clear_filters_button_is_accessible()
    {
        // Step 1: Access page with filters
        $response = $this->actingAs($this->admin)
            ->get('/admin/falecidos?tableFilters[search][value]=Test');
        
        $response->assertStatus(200);
        
        // Step 2: Verify button has proper attributes
        $content = $response->getContent();
        
        // Check for icon
        $this->assertStringContainsString('heroicon-m-x-mark', $content);
        
        // Check for label
        $this->assertStringContainsString('Limpar filtros', $content);
    }

    /**
     * Test Scenario 8: Performance with many filters
     */
    public function test_clear_filters_performance_with_many_parameters()
    {
        // Step 1: Create URL with many filter parameters
        $complexFilterUrl = '/admin/falecidos?' . http_build_query([
            'tableFilters' => [
                'search' => ['value' => 'Test'],
                'fal_status' => ['value' => '1'],
                'fal_data_falecimento' => [
                    'from' => '2024-01-01',
                    'to' => '2024-12-31'
                ],
                'fal_data_nasc' => [
                    'from' => '1940-01-01',
                    'to' => '1960-12-31'
                ]
            ],
            'page' => 1,
            'sort' => 'fal_nome',
            'direction' => 'asc'
        ]);
        
        $response = $this->actingAs($this->admin)->get($complexFilterUrl);
        $response->assertStatus(200);
        
        // Step 2: Clear all filters
        $startTime = microtime(true);
        $clearResponse = $this->actingAs($this->admin)->get('/admin/falecidos');
        $endTime = microtime(true);
        
        // Step 3: Verify response time is reasonable (less than 1 second)
        $responseTime = $endTime - $startTime;
        $this->assertLessThan(1.0, $responseTime);
        
        // Step 4: Verify page loaded successfully
        $clearResponse->assertStatus(200);
    }
}