<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "=== DEBUG DE LOGIN ===\n\n";

// Verificar se existe o usuário proprietario@email.com
$email = 'proprietario@email.com';
$senha = '123';

echo "Procurando usuário: {$email}\n";
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ USUÁRIO NÃO ENCONTRADO!\n";
    echo "Usuários disponíveis:\n";
    $users = User::all(['id', 'name', 'email', 'status']);
    foreach ($users as $u) {
        echo "- {$u->email} (Status: {$u->status})\n";
    }
    exit;
}

echo "✅ Usuário encontrado!\n";
echo "ID: {$user->id}\n";
echo "Nome: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Status: {$user->status}\n";
echo "Hash da senha: " . substr($user->password, 0, 30) . "...\n\n";

// Testar verificação de senha
echo "=== TESTE DE SENHA ===\n";
echo "Testando senha: '{$senha}'\n";

$senhaCorreta = Hash::check($senha, $user->password);
echo "Verificação de senha: " . ($senhaCorreta ? "✅ CORRETA" : "❌ INCORRETA") . "\n\n";

// Testar diferentes senhas possíveis
$senhasPossiveis = ['123', 'password', 'admin', 'proprietario'];
echo "=== TESTANDO SENHAS POSSÍVEIS ===\n";
foreach ($senhasPossiveis as $testeSenha) {
    $resultado = Hash::check($testeSenha, $user->password);
    echo "Senha '{$testeSenha}': " . ($resultado ? "✅ CORRETA" : "❌ INCORRETA") . "\n";
}

echo "\n=== TESTE DE AUTENTICAÇÃO ===\n";
$credentials = [
    'email' => $email,
    'password' => $senha
];

echo "Tentando autenticar com:\n";
echo "Email: {$credentials['email']}\n";
echo "Senha: {$credentials['password']}\n";

$authResult = Auth::attempt($credentials);
echo "Resultado da autenticação: " . ($authResult ? "✅ SUCESSO" : "❌ FALHOU") . "\n";

if (!$authResult) {
    echo "\n=== DIAGNÓSTICO ADICIONAL ===\n";
    
    // Verificar se o usuário está ativo
    if ($user->status !== 1) {
        echo "❌ Usuário está INATIVO (status: {$user->status})\n";
    } else {
        echo "✅ Usuário está ATIVO\n";
    }
    
    // Verificar roles
    $roles = $user->roles->pluck('name')->toArray();
    echo "Roles do usuário: " . implode(', ', $roles) . "\n";
    
    // Verificar se pode acessar painel
    try {
        $canAccess = $user->canAccessPanel(app(\Filament\Panel::class));
        echo "Pode acessar painel: " . ($canAccess ? "✅ SIM" : "❌ NÃO") . "\n";
    } catch (Exception $e) {
        echo "Erro ao verificar acesso ao painel: " . $e->getMessage() . "\n";
    }
}

echo "\n=== INFORMAÇÕES DO SISTEMA ===\n";
echo "Laravel Version: " . app()->version() . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Hash Driver: " . config('hashing.driver') . "\n"; 