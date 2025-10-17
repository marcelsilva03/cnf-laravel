<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ClientesFaturamentoSeeder extends Seeder
{
    /**
     * Criar clientes de teste para faturamento
     */
    public function run(): void
    {
        $this->command->info('🔄 Criando clientes de teste para faturamento...');

        // Garantir que as roles existem
        $roles = ['clienteapi', 'solicitante', 'pesquisador'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Clientes de teste
        $clientes = [
            [
                'name' => 'Cliente API Teste',
                'email' => 'cliente.api@teste.com',
                'role' => 'clienteapi'
            ],
            [
                'name' => 'João Solicitante',
                'email' => 'joao.solicitante@teste.com',
                'role' => 'solicitante'
            ],
            [
                'name' => 'Maria Pesquisadora',
                'email' => 'maria.pesquisadora@teste.com',
                'role' => 'pesquisador'
            ],
            [
                'name' => 'Carlos Silva',
                'email' => 'carlos.silva@empresa.com',
                'role' => 'clienteapi'
            ],
            [
                'name' => 'Ana Santos',
                'email' => 'ana.santos@cartorio.com',
                'role' => 'pesquisador'
            ]
        ];

        foreach ($clientes as $clienteData) {
            // Verificar se já existe
            $existingUser = User::where('email', $clienteData['email'])->first();
            
            if ($existingUser) {
                $this->command->warn("Cliente {$clienteData['email']} já existe. Pulando...");
                continue;
            }

            // Criar usuário
            $user = User::create([
                'name' => $clienteData['name'],
                'email' => $clienteData['email'],
                'password' => Hash::make('password123'),
                'status' => User::STATUS['ATIVO'],
            ]);

            // Atribuir role
            $user->assignRole($clienteData['role']);

            $this->command->info("✅ Cliente criado: {$user->name} ({$user->email}) - {$clienteData['role']}");
        }

        $this->command->info('🎉 Clientes de teste criados com sucesso!');
        $this->command->newLine();
        
        // Mostrar estatísticas
        $totalClientes = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['clienteapi', 'solicitante', 'pesquisador']);
        })
        ->where('status', User::STATUS['ATIVO'])
        ->count();

        $this->command->info("📊 Total de clientes disponíveis para faturamento: {$totalClientes}");
        $this->command->info("💡 Acesse: /admin/faturamentos/create para testar");
    }
} 