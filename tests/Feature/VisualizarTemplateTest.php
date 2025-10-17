<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserRole;

class VisualizarTemplateTest extends TestCase
{
    public function test_visualizar_template()
    {
        // Retrieve an existing user
        $user = User::where('email', 'admin@email.com')->first(); // Replace with an actual email of an existing user
        Log::info('Retrieved user:', ['user' => $user]); // Log user retrieval

        // Log in the user
        $this->actingAs($user);

        // Assert that the user is authenticated
        $this->assertAuthenticatedAs($user);

        // Simulate clicking the "Visualizar" button for the email template
        $templateId = 1; // Replace with the actual template ID you want to test

        // Set the XSRF-TOKEN and cnf_session in the headers using the provided tokens
        $response = $this->withHeaders([
            'Cookie' => 'XSRF-TOKEN=eyJpdiI6ImhpN2xJQU5NRHlKc1lUdE1Db1dScHc9PSIsInZhbHVlIjoiTDJCM0xMRUk5WFhySXhIaGNNcFFKYXA2ZjVGQThPTlNiTWVxbllmamNxSUlTemEyQ1hvVTJMamcwMXZQNUYrVEowc21KR0hZWG04VkViK0VRb25FSFplU1B2S3dWNnF0aW9uVnZMOEJBQ0lTdHFRNTM3T0NsUmZKNU43ZlVNZ3UiLCJtYWMiOiJkZTU1OTE4MDZlOTY4NTNhOGM3OGYzMmE4MGNmZDZkM2VjYjMzMGY2YWJlZTdiOTZlZGJmY2NhYjVmYTU5NjViIiwidGFnIjoiIn0%3D; cnf_session=eyJpdiI6IjFMcVVldVpwRG1LUG9Ec2p5RlhHd2c9PSIsInZhbHVlIjoiMVZsQVY5blREU2J1N2prZUhrVHZrTnJFc2UzcU40MzdQZ1l1a2NSTWdCNEp4RHN6Ykk0U3U5VThocEJzS2xSTnBtNmY0ZkVWeWpoWVhDb296NmI2Mmk5eXE5U1JaeDN1ZTJrbzVjZlVnQXM2WWRNRWM4RFJSNTh1dEh6bEtwd3AiLCJtYWMiOiJiZTM5NWUzOGVhNGJkMjQ1N2U4YWNlOGY5YzQzM2QwNTZkM2U0NzliMGM2MDRiOWZiMmIwN2IyMmFhODNjZDgzIiwidGFnIjoiIn0%3D',
        ])->get("/dashboard/email-templates/{$templateId}/visualizar");

        // Log response status
        Log::info('Response status for visualizing template:', ['status' => $response->status()]); // Log response status

        // Assert that the response is successful and contains the expected content
        $response->assertStatus(200);
        $response->assertSee('Expected Content'); // Replace with actual content to check
    }

    public function test_dashboard_access()
    {
        // Log the request details
        Log::info('Requesting dashboard access:', ['url' => '/dashboard']);

        // Retrieve an existing user
        $user = User::where('email', 'admin@email.com')->first(); // Replace with an actual email of an existing user
        Log::info('Retrieved user for dashboard access:', ['user' => $user]); // Log user retrieval

        // Log in the user
        $this->actingAs($user);

        // Assert that the user is authenticated
        $this->assertAuthenticatedAs($user);

        // Set the XSRF-TOKEN and cnf_session in the headers using the provided tokens
        $response = $this->withHeaders([
            'Cookie' => 'XSRF-TOKEN=eyJpdiI6ImhpN2xJQU5NRHlKc1lUdE1Db1dScHc9PSIsInZhbHVlIjoiTDJCM0xMRUk5WFhySXhIaGNNcFFKYXA2ZjVGQThPTlNiTWVxbllmamNxSUlTemEyQ1hvVTJMamcwMXZQNUYrVEowc21KR0hZWG04VkViK0VRb25FSFplU1B2S3dWNnF0aW9uVnZMOEJBQ0lTdHFRNTM3T0NsUmZKNU43ZlVNZ3UiLCJtYWMiOiJkZTU1OTE4MDZlOTY4NTNhOGM3OGYzMmE4MGNmZDZkM2VjYjMzMGY2YWJlZTdiOTZlZGJmY2NhYjVmYTU5NjViIiwidGFnIjoiIn0%3D; cnf_session=eyJpdiI6IjFMcVVldVpwRG1LUG9Ec2p5RlhHd2c9PSIsInZhbHVlIjoiMVZsQVY5blREU2J1N2prZUhrVHZrTnJFc2UzcU40MzdQZ1l1a2NSTWdCNEp4RHN6Ykk0U3U5VThocEJzS2xSTnBtNmY0ZkVWeWpoWVhDb296NmI2Mmk5eXE5U1JaeDN1ZTJrbzVjZlVnQXM2WWRNRWM4RFJSNTh1dEh6bEtwd3AiLCJtYWMiOiJiZTM5NWUzOGVhNGJkMjQ1N2U4YWNlOGY5YzQzM2QwNTZkM2U0NzliMGM2MDRiOWZiMmIwN2IyMmFhODNjZDgzIiwidGFnIjoiIn0%3D',
        ])->get('/dashboard');

        // Log response status
        Log::info('Response status for dashboard access:', ['status' => $response->status()]); // Log response status

        // Assert that the response is successful
        $response->assertStatus(200);
    }
}
