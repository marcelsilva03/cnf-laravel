<?php

namespace Tests\Feature;

use App\Models\Falecido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FalecidosResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $adminRole = Role::create(['name' => 'admin']);
        Role::create(['name' => 'moderador']);
        Role::create(['name' => 'pesquisador']);
    }

    public function test_admin_can_access_falecidos_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $response = $this->actingAs($admin)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
    }

    public function test_clear_filters_button_renders_without_error()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $response = $this->actingAs($admin)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
        $response->assertSee('Limpar filtros');
    }

    public function test_clear_filters_redirects_to_same_page()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $response = $this->actingAs($admin)
            ->get('/admin/falecidos?tableFilters[search][value]=test');
        
        $response->assertStatus(200);
        
        $content = $response->getContent();
        $this->assertStringContainsString('Limpar filtros', $content);
        $this->assertStringContainsString('/admin/falecidos', $content);
    }

    public function test_moderador_can_access_falecidos_list()
    {
        $moderador = User::factory()->create();
        $moderador->assignRole('moderador');
        
        $response = $this->actingAs($moderador)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
    }

    public function test_pesquisador_can_access_falecidos_list()
    {
        $pesquisador = User::factory()->create();
        $pesquisador->assignRole('pesquisador');
        
        $response = $this->actingAs($pesquisador)
            ->get('/admin/falecidos');
        
        $response->assertStatus(200);
    }

    public function test_unauthorized_user_cannot_access_falecidos()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get('/admin/falecidos');
        
        $response->assertStatus(403);
    }
}