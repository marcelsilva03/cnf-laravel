<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    protected $signature = 'make:test-user';

    protected $description = 'Create a test user for development';

    public function handle()
    {
        // Create admin role if it doesn't exist
        $role = UserRole::firstOrCreate(
            ['name' => 'admin'],
            ['label' => 'Administrator']
        );

        // Create or update test user
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
                'role_id' => $role->id,
            ]
        );

        $this->info('Test user created successfully!');
        $this->info('Email: test@example.com');
        $this->info('Password: password123');
    }
} 