<?php

// Script para criar clientes de teste em produção
// Acesse via: https://novo.falecidosnobrasil.org.br/criar-clientes-teste.php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Criar Clientes de Teste</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .button { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 5px; font-family: monospace; }
    </style>
</head>
<body>
    <h1>🔧 CRIAR CLIENTES DE TESTE</h1>
    <hr>

    <?php
    $criar = isset($_GET['criar']) && $_GET['criar'] === 'sim';
    
    if (!$criar) {
        echo "<p>Este script vai criar 5 clientes de teste para o sistema de faturamento.</p>";
        echo "<p><strong>⚠️ ATENÇÃO:</strong> Execute apenas se não houver clientes disponíveis!</p>";
        echo "<p><a href='?criar=sim' class='button'>🚀 CRIAR CLIENTES DE TESTE</a></p>";
        echo "<p><a href='debug-clientes-producao.php'>🔍 Verificar clientes existentes</a></p>";
    } else {
        try {
            echo "<h2>🔄 CRIANDO CLIENTES DE TESTE...</h2>";
            
            // Garantir que as roles existem
            $roles = ['clienteapi', 'solicitante', 'pesquisador'];
            foreach ($roles as $roleName) {
                Role::firstOrCreate(['name' => $roleName]);
                echo "<p>✅ Role '{$roleName}' verificada</p>";
            }
            
            // Clientes de teste
            $clientes = [
                [
                    'name' => 'Cliente API Teste',
                    'email' => 'cliente.api@teste.cnf.com',
                    'role' => 'clienteapi'
                ],
                [
                    'name' => 'João Solicitante',
                    'email' => 'joao.solicitante@teste.cnf.com',
                    'role' => 'solicitante'
                ],
                [
                    'name' => 'Maria Pesquisadora',
                    'email' => 'maria.pesquisadora@teste.cnf.com',
                    'role' => 'pesquisador'
                ],
                [
                    'name' => 'Carlos Silva Empresa',
                    'email' => 'carlos.silva@empresa.cnf.com',
                    'role' => 'clienteapi'
                ],
                [
                    'name' => 'Ana Santos Cartório',
                    'email' => 'ana.santos@cartorio.cnf.com',
                    'role' => 'pesquisador'
                ]
            ];
            
            $criados = 0;
            $existentes = 0;
            
            foreach ($clientes as $clienteData) {
                // Verificar se já existe
                $existingUser = User::where('email', $clienteData['email'])->first();
                
                if ($existingUser) {
                    echo "<p class='warning'>⚠️ Cliente {$clienteData['email']} já existe</p>";
                    $existentes++;
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
                
                echo "<p class='success'>✅ Cliente criado: {$user->name} ({$user->email}) - {$clienteData['role']}</p>";
                $criados++;
            }
            
            echo "<hr>";
            echo "<h2>📊 RESUMO</h2>";
            echo "<p><strong>Clientes criados:</strong> {$criados}</p>";
            echo "<p><strong>Clientes já existentes:</strong> {$existentes}</p>";
            
            if ($criados > 0) {
                echo "<h2>🎯 PRÓXIMOS PASSOS</h2>";
                echo "<ol>";
                echo "<li>Volte para: <a href='/admin/faturamentos/create'>Criar Faturamento</a></li>";
                echo "<li>Clique no campo 'Cliente'</li>";
                echo "<li>Verifique se os clientes aparecem na lista</li>";
                echo "</ol>";
                
                echo "<h2>📋 DADOS PARA TESTE</h2>";
                echo "<div class='code'>";
                echo "Nome: Cliente API Teste<br>";
                echo "Email: cliente.api@teste.cnf.com<br>";
                echo "Tipo: Cliente API<br>";
                echo "Senha: password123";
                echo "</div>";
            }
            
        } catch (Exception $e) {
            echo "<p class='error'><strong>❌ ERRO:</strong> " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
    }
    ?>

    <hr>
    <p><small>🕒 Executado em: <?= date('d/m/Y H:i:s') ?></small></p>
    <p><small>📍 <a href="debug-clientes-producao.php">🔍 Verificar clientes</a> | <a href="/admin/faturamentos/create">💰 Criar Faturamento</a></small></p>
</body>
</html> 