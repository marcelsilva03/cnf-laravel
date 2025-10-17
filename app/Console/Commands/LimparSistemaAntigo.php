<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LimparSistemaAntigo extends Command
{
    protected $signature = 'system:clean-old-roles';
    protected $description = 'Limpa completamente o sistema antigo de roles e migra para Spatie';

    public function handle()
    {
        $this->info('🧹 INICIANDO LIMPEZA DO SISTEMA ANTIGO DE ROLES');
        $this->newLine();

        // 1. Limpar campo role_id de todos os usuários
        $this->info('1️⃣ Limpando campo role_id dos usuários...');
        $usersWithRoleId = User::whereNotNull('role_id')->count();
        
        if ($usersWithRoleId > 0) {
            User::whereNotNull('role_id')->update(['role_id' => null]);
            $this->info("   ✅ {$usersWithRoleId} usuários limpos");
        } else {
            $this->info("   ✅ Nenhum usuário com role_id encontrado");
        }

        // 2. Verificar usuários sem roles no Spatie
        $this->info('2️⃣ Verificando usuários sem roles no sistema Spatie...');
        $usersWithoutRoles = User::doesntHave('roles')->get();
        
        if ($usersWithoutRoles->count() > 0) {
            $this->warn("   ⚠️  {$usersWithoutRoles->count()} usuários sem roles encontrados:");
            foreach ($usersWithoutRoles as $user) {
                $this->line("      - {$user->email} (ID: {$user->id})");
            }
            
            if ($this->confirm('Deseja atribuir role "solicitante" para estes usuários?')) {
                foreach ($usersWithoutRoles as $user) {
                    $user->assignRole('solicitante');
                    $this->info("      ✅ Role 'solicitante' atribuído para {$user->email}");
                }
            }
        } else {
            $this->info("   ✅ Todos os usuários têm roles no sistema Spatie");
        }

        // 3. Verificar usuários com múltiplos roles problemáticos
        $this->info('3️⃣ Verificando usuários com múltiplos roles...');
        $problematicUsers = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['pesquisador', 'solicitante']);
        })->get();

        foreach ($problematicUsers as $user) {
            $roles = $user->roles->pluck('name')->toArray();
            if (in_array('pesquisador', $roles) && in_array('solicitante', $roles)) {
                $this->warn("   ⚠️  Usuário {$user->email} tem roles conflitantes: " . implode(', ', $roles));
                
                if ($this->confirm("Manter apenas 'solicitante' para {$user->email}?")) {
                    $user->syncRoles(['solicitante']);
                    $this->info("      ✅ Roles limpos, mantido apenas 'solicitante'");
                }
            }
        }

        // 4. Relatório final
        $this->newLine();
        $this->info('📊 RELATÓRIO FINAL:');
        
        $totalUsers = User::count();
        $usersWithRoles = User::has('roles')->count();
        $usersWithoutRoles = User::doesntHave('roles')->count();
        
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total de usuários', $totalUsers],
                ['Usuários com roles (Spatie)', $usersWithRoles],
                ['Usuários sem roles', $usersWithoutRoles],
                ['Usuários com role_id (sistema antigo)', User::whereNotNull('role_id')->count()],
            ]
        );

        // 5. Verificar roles por tipo
        $this->info('📋 DISTRIBUIÇÃO DE ROLES:');
        $roleStats = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name', DB::raw('count(*) as total'))
            ->groupBy('roles.name')
            ->orderBy('total', 'desc')
            ->get();

        $roleData = [];
        foreach ($roleStats as $stat) {
            $roleData[] = [$stat->name, $stat->total];
        }
        
        $this->table(['Role', 'Usuários'], $roleData);

        $this->newLine();
        $this->info('✅ LIMPEZA CONCLUÍDA COM SUCESSO!');
        $this->info('🎯 Sistema agora usa apenas Spatie Laravel Permission');
        
        return 0;
    }
} 