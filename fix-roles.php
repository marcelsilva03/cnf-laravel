<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

echo "=== CORRIGINDO ROLES DOS USUÁRIOS ===\n\n";

// Mapear usuários para suas roles corretas
$userRoleMap = [
    'admin@email.com' => 'admin',
    'moderador@email.com' => 'moderador',
    'pesquisador@email.com' => 'pesquisador',
    'financeiro@email.com' => 'financeiro',
    'clienteapi@email.com' => 'clienteapi',
    'solicitante@email.com' => 'solicitante',
    'proprietario@email.com' => 'proprietario',
];

foreach ($userRoleMap as $email => $roleName) {
    echo "Processando usuário: {$email}\n";
    
    $user = User::where('email', $email)->first();
    if (!$user) {
        echo "  ❌ Usuário não encontrado\n";
        continue;
    }
    
    // Verificar se a role existe
    $role = Role::where('name', $roleName)->first();
    if (!$role) {
        echo "  ⚠️ Role '{$roleName}' não existe, criando...\n";
        $role = Role::create(['name' => $roleName]);
    }
    
    // Remover todas as roles atuais
    $user->syncRoles([]);
    
    // Atribuir a role correta
    $user->assignRole($role);
    
    echo "  ✅ Role '{$roleName}' atribuída com sucesso\n";
}

echo "\n=== VERIFICAÇÃO FINAL ===\n";
$users = User::with('roles')->where('status', 1)->get();

foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->toArray();
    echo "- {$user->email}: " . (empty($roles) ? "Nenhuma role" : implode(', ', $roles)) . "\n";
}

echo "\n✅ Correção de roles concluída!\n"; 