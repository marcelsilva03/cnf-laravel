<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserRoleSeeder::class,      // Create roles first (old system)
            RolesTableSeeder::class,    // Create Spatie roles
            UserSeeder::class,          // Create system users
            EmailTemplateSeeder::class, // Create email templates
        ]);
    }
} 