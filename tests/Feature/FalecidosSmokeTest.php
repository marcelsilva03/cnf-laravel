<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class FalecidosSmokeTest extends TestCase
{
    /**
     * Simple smoke test that checks if the Clear Filters button exists
     * without modifying the database
     */
    public function test_clear_filters_button_exists_for_admin()
    {
        // First, let's check if we have any users at all
        $userCount = User::count();
        echo "Total users in database: {$userCount}\n";
        
        // Check if we have the admin role
        $adminRoleExists = \DB::table('roles')->where('name', 'admin')->exists();
        echo "Admin role exists: " . ($adminRoleExists ? 'Yes' : 'No') . "\n";
        
        // Try different approaches to find an admin
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->first();
        
        // If no admin found via Spatie, try the user_roles table
        if (!$admin) {
            echo "No admin found via Spatie roles, trying user_roles table...\n";
            $adminRoleId = \DB::table('user_roles')->where('name', 'admin')->value('id');
            if ($adminRoleId) {
                $admin = User::where('role_id', $adminRoleId)->first();
            }
        }
        
        // If still no admin, try to find any user with ID 1 (often the first admin)
        if (!$admin) {
            echo "No admin found via user_roles, trying user with ID 1...\n";
            $admin = User::find(1);
        }
        
        // If no admin exists, fail the test with helpful message
        if (!$admin) {
            $this->fail('No admin user found in the database. Please ensure there is at least one admin user.');
        }
        
        echo "Using admin user: {$admin->email}\n";
        
        // Act as admin and check the page
        $response = $this->actingAs($admin)
            ->get('/admin/falecidos');
        
        // Assert the page loads successfully
        $response->assertStatus(200);
        
        // Assert the Clear Filters button text exists
        $response->assertSee('Limpar filtros');
        
        // Assert no 405 error
        $response->assertDontSee('405 Method Not Allowed');
    }
}