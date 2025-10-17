<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'label' => 'Admin'],
            ['name' => 'pesquisador', 'label' => 'Pesquisador'],
            ['name' => 'moderador', 'label' => 'Moderador'],
            ['name' => 'financeiro', 'label' => 'Financeiro'],
            ['name' => 'clienteapi', 'label' => 'Cliente API'],
            ['name' => 'socio-gestor', 'label' => 'Sócio-Gestor'],
            ['name' => 'solicitante', 'label' => 'Solicitante'],
        ];

        foreach ($roles as $role) {
            DB::table('user_roles')->updateOrInsert(
                ['name' => $role['name']],
                ['label' => $role['label']]
            );
        }
    }
}
