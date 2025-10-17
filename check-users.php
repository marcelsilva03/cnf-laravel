<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== VERIFICAÇÃO DE USUÁRIOS ===\n";
echo "Total de usuários: " . User::count() . "\n\n";

$users = User::all(['id', 'name', 'email', 'status']);

if ($users->count() > 0) {
    echo "Usuários encontrados:\n";
    foreach ($users as $user) {
        echo "ID: {$user->id} | Nome: {$user->name} | Email: {$user->email} | Status: {$user->status}\n";
    }
} else {
    echo "Nenhum usuário encontrado no banco de dados.\n";
}

// Verificar se existe o usuário proprietario@email.com
$proprietario = User::where('email', 'proprietario@email.com')->first();
if ($proprietario) {
    echo "\n=== USUÁRIO PROPRIETÁRIO ENCONTRADO ===\n";
    echo "ID: {$proprietario->id}\n";
    echo "Nome: {$proprietario->name}\n";
    echo "Email: {$proprietario->email}\n";
    echo "Status: {$proprietario->status}\n";
    echo "Senha hash: " . substr($proprietario->password, 0, 20) . "...\n";
    
    // Verificar roles
    $roles = $proprietario->roles->pluck('name')->toArray();
    echo "Roles: " . implode(', ', $roles) . "\n";
} else {
    echo "\n=== USUÁRIO PROPRIETÁRIO NÃO ENCONTRADO ===\n";
} 