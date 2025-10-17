<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== TESTE DE LOGIN WEB ===\n\n";

// Verificar todos os usuários disponíveis
echo "Usuários disponíveis para login:\n";
$users = User::where('status', 1)->get(['id', 'name', 'email']);

foreach ($users as $user) {
    echo "- {$user->email} ({$user->name})\n";
    
    // Testar senha '123' para cada usuário
    $senhaCorreta = Hash::check('123', User::find($user->id)->password);
    echo "  Senha '123': " . ($senhaCorreta ? "✅ CORRETA" : "❌ INCORRETA") . "\n";
    
    // Verificar roles
    $userWithRoles = User::with('roles')->find($user->id);
    $roles = $userWithRoles->roles->pluck('name')->toArray();
    echo "  Roles: " . (empty($roles) ? "Nenhuma" : implode(', ', $roles)) . "\n";
    
    // Verificar se pode acessar painel Filament
    try {
        $canAccess = $userWithRoles->canAccessPanel(app(\Filament\Panel::class));
        echo "  Acesso Filament: " . ($canAccess ? "✅ PERMITIDO" : "❌ NEGADO") . "\n";
    } catch (Exception $e) {
        echo "  Acesso Filament: ❌ ERRO - " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "=== INSTRUÇÕES DE LOGIN ===\n";
echo "1. Para acessar o painel administrativo (Filament): http://localhost:8080/admin/login\n";
echo "2. Para acessar o login público: http://localhost:8080/login\n";
echo "3. Credenciais para teste:\n";
echo "   - Email: proprietario@email.com\n";
echo "   - Senha: 123\n\n";

echo "=== VERIFICAÇÃO DE CONFIGURAÇÃO ===\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "APP_ENV: " . config('app.env') . "\n";
echo "DB_CONNECTION: " . config('database.default') . "\n";
echo "SESSION_DRIVER: " . config('session.driver') . "\n"; 