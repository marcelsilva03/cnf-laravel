<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserRoleSeeder::class,      // Create roles first (old system)
            RolesTableSeeder::class,    // Create Spatie roles
            UserSeeder::class,          // Create system users
            EmailTemplateSeeder::class, // Create email templates
            
            // Optional seeders for development/testing
            
            FalecidoSeeder::class,
            CartorioSeeder::class,
            ComunicadoDeObitoSeeder::class,
            ComunicadoDeErroSeeder::class,
            PrecoCertidoesSeeder::class,
            SolicitacaoSeeder::class,
            HomenagemSeeder::class,
            PlanoSeeder::class,
            ApiRequisicaoSeeder::class,
            FaturamentoSeeder::class,
            
        ]);
    }
}
