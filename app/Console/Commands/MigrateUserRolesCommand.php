<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class MigrateUserRolesCommand extends Command
{
    protected $signature = 'users:migrate-roles {--dry-run : Executar sem fazer alterações} {--force : Forçar migração mesmo se já existirem roles}';
    protected $description = 'Migra roles do sistema antigo (user_roles) para o sistema Spatie Permission';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('🔄 Iniciando migração de roles do sistema antigo para Spatie Permission');
        $this->info('Modo: ' . ($dryRun ? 'DRY RUN (sem alterações)' : 'PRODUÇÃO'));
        $this->newLine();

        try {
            // 1. Verificar se as tabelas existem
            if (!$this->checkTables()) {
                return 1;
            }

            // 2. Migrar roles da tabela user_roles para roles
            $this->migrateRolesTable($dryRun);

            // 3. Migrar associações de usuários
            $this->migrateUserRoleAssociations($dryRun, $force);

            // 4. Verificar integridade
            $this->verifyMigration();

            $this->newLine();
            $this->info('✅ Migração concluída com sucesso!');
            
            if (!$dryRun) {
                $this->warn('💡 Dica: Execute "php artisan cache:clear" para limpar o cache de permissões');
            }

        } catch (\Exception $e) {
            $this->error('❌ Erro durante a migração: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function checkTables(): bool
    {
        $this->info('📋 Verificando tabelas necessárias...');

        $tables = ['user_roles', 'users', 'roles', 'model_has_roles'];
        $missing = [];

        foreach ($tables as $table) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $missing[] = $table;
            }
        }

        if (!empty($missing)) {
            $this->error('❌ Tabelas não encontradas: ' . implode(', ', $missing));
            return false;
        }

        $this->info('✅ Todas as tabelas necessárias encontradas');
        return true;
    }

    private function migrateRolesTable(bool $dryRun): void
    {
        $this->info('🔄 Migrando roles da tabela user_roles...');

        $userRoles = DB::table('user_roles')->get();
        $migrated = 0;
        $skipped = 0;

        foreach ($userRoles as $userRole) {
            $existingRole = Role::where('name', $userRole->name)->first();

            if ($existingRole) {
                $this->line("⏭️  Role '{$userRole->name}' já existe no sistema Spatie");
                $skipped++;
                continue;
            }

            if (!$dryRun) {
                Role::create([
                    'name' => $userRole->name,
                    'guard_name' => 'web'
                ]);
            }

            $this->line("✅ Role '{$userRole->name}' " . ($dryRun ? '[DRY RUN]' : 'migrado'));
            $migrated++;
        }

        $this->info("📊 Roles: {$migrated} migrados, {$skipped} já existiam");
    }

    private function migrateUserRoleAssociations(bool $dryRun, bool $force): void
    {
        $this->info('🔄 Migrando associações usuário-role...');

        // Buscar usuários que têm role_id definido
        $users = User::whereNotNull('role_id')->with('roles')->get();
        
        if ($users->isEmpty()) {
            $this->warn('⚠️  Nenhum usuário com role_id encontrado');
            return;
        }

        $migrated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                // Buscar o role antigo
                $oldRole = DB::table('user_roles')->where('id', $user->role_id)->first();
                
                if (!$oldRole) {
                    $this->warn("⚠️  Role ID {$user->role_id} não encontrado para usuário {$user->email}");
                    $errors++;
                    continue;
                }

                // Verificar se o usuário já tem roles no sistema Spatie
                if ($user->roles->isNotEmpty() && !$force) {
                    $currentRoles = $user->roles->pluck('name')->implode(', ');
                    $this->line("⏭️  Usuário {$user->email} já tem roles: {$currentRoles}");
                    $skipped++;
                    continue;
                }

                // Buscar o role no sistema Spatie
                $newRole = Role::where('name', $oldRole->name)->first();
                
                if (!$newRole) {
                    $this->warn("⚠️  Role '{$oldRole->name}' não encontrado no sistema Spatie");
                    $errors++;
                    continue;
                }

                if (!$dryRun) {
                    // Se force estiver ativo, limpar roles existentes
                    if ($force && $user->roles->isNotEmpty()) {
                        $user->syncRoles([]);
                    }
                    
                    // Atribuir o novo role
                    $user->assignRole($newRole->name);
                }

                $this->line("✅ Usuário {$user->email} -> Role '{$oldRole->name}' " . ($dryRun ? '[DRY RUN]' : 'migrado'));
                $migrated++;

            } catch (\Exception $e) {
                $this->error("❌ Erro ao migrar usuário {$user->email}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("📊 Usuários: {$migrated} migrados, {$skipped} pulados, {$errors} erros");
    }

    private function verifyMigration(): void
    {
        $this->info('🔍 Verificando integridade da migração...');

        // Contar usuários com roles no sistema antigo
        $usersWithOldRoles = User::whereNotNull('role_id')->count();
        
        // Contar usuários com roles no sistema Spatie
        $usersWithNewRoles = User::whereHas('roles')->count();

        $this->table(
            ['Métrica', 'Quantidade'],
            [
                ['Usuários com role_id (sistema antigo)', $usersWithOldRoles],
                ['Usuários com roles Spatie', $usersWithNewRoles],
                ['Total de roles disponíveis', Role::count()],
                ['Total de usuários', User::count()],
            ]
        );

        // Verificar usuários sem roles
        $usersWithoutRoles = User::whereDoesntHave('roles')->where('status', 1)->count();
        
        if ($usersWithoutRoles > 0) {
            $this->warn("⚠️  {$usersWithoutRoles} usuários ativos sem roles no sistema Spatie");
        }

        // Listar roles disponíveis
        $roles = Role::pluck('name')->toArray();
        $this->info('📋 Roles disponíveis no sistema Spatie: ' . implode(', ', $roles));
    }
} 