<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Moderador',
                'email' => 'moderador@email.com',
                'password' => Hash::make('123'),
                'role' => 'moderador'
            ],
            [
                'name' => 'Pesquisador',
                'email' => 'pesquisador@email.com',
                'password' => Hash::make('123'),
                'role' => 'pesquisador'
            ],
            [
                'name' => 'Financeiro',
                'email' => 'financeiro@email.com',
                'password' => Hash::make('123'),
                'role' => 'financeiro'
            ],
            [
                'name' => 'Cliente API',
                'email' => 'clienteapi@email.com',
                'password' => Hash::make('123'),
                'role' => 'clienteapi'
            ],
            [
                'name' => 'Proprietário',
                'email' => 'proprietario@email.com',
                'password' => Hash::make('123'),
                'role' => 'proprietario'
            ],
            [
                'name' => 'Solicitante',
                'email' => 'solicitante@email.com',
                'password' => Hash::make('123'),
                'role' => 'solicitante'
            ],
            [
                'name' => 'Sócio-Gestor',
                'email' => 'socio-gestor@email.com',
                'password' => Hash::make('123'),
                'role' => 'socio-gestor'
            ],
        ];

        foreach ($users as $userData) {
            // CORREÇÃO: Usar apenas sistema Spatie, removido sistema antigo (role_id)
            
            // Create or update the user
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'status' => User::STATUS['ATIVO']  // Usar constante em vez de hardcode
                ]
            );
            
            // Assign the Spatie role (sistema único)
            $role = Role::where('name', $userData['role'])->first();
            if ($role) {
                // Limpar roles existentes e definir apenas o correto
                $user->syncRoles([$userData['role']]);
            }
        }
    }
}

