<?php

// Script para debug de clientes em produção
// Acesse via: https://novo.falecidosnobrasil.org.br/debug-clientes-producao.php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug - Clientes para Faturamento</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 5px; font-family: monospace; }
    </style>
</head>
<body>
    <h1>🔍 DEBUG - CLIENTES PARA FATURAMENTO</h1>
    <hr>

    <?php
    try {
        echo "<h2>📊 ESTATÍSTICAS GERAIS</h2>";
        
        // Total de usuários
        $totalUsuarios = User::count();
        echo "<p><strong>Total de usuários no sistema:</strong> {$totalUsuarios}</p>";
        
        // Usuários ativos
        $usuariosAtivos = User::where('status', User::STATUS['ATIVO'])->count();
        echo "<p><strong>Usuários ativos:</strong> {$usuariosAtivos}</p>";
        
        // Clientes para faturamento
        $clientesDisponiveis = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['clienteapi', 'solicitante', 'pesquisador']);
        })
        ->where('status', User::STATUS['ATIVO'])
        ->with('roles')
        ->get();
        
        $totalClientes = $clientesDisponiveis->count();
        
        if ($totalClientes > 0) {
            echo "<p class='success'><strong>✅ Clientes disponíveis para faturamento:</strong> {$totalClientes}</p>";
            
            echo "<h2>📋 LISTA DE CLIENTES DISPONÍVEIS</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Role</th><th>Status</th></tr>";
            
            foreach ($clientesDisponiveis as $cliente) {
                $role = $cliente->roles->first();
                $roleName = $role ? $role->name : 'SEM ROLE';
                $statusText = $cliente->status === User::STATUS['ATIVO'] ? 'ATIVO' : 'INATIVO';
                
                echo "<tr>";
                echo "<td>{$cliente->id}</td>";
                echo "<td>{$cliente->name}</td>";
                echo "<td>{$cliente->email}</td>";
                echo "<td>{$roleName}</td>";
                echo "<td>{$statusText}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<h2>🎯 TESTE MANUAL</h2>";
            echo "<p>Para testar manualmente, use estes dados:</p>";
            $primeiro = $clientesDisponiveis->first();
            echo "<div class='code'>";
            echo "Cliente ID: {$primeiro->id}<br>";
            echo "Nome: {$primeiro->name}<br>";
            echo "Email: {$primeiro->email}<br>";
            echo "Role: " . ($primeiro->roles->first()->name ?? 'N/A');
            echo "</div>";
            
        } else {
            echo "<p class='error'><strong>❌ NÃO HÁ CLIENTES DISPONÍVEIS!</strong></p>";
            
            echo "<h2>🔍 ANÁLISE DETALHADA</h2>";
            
            // Verificar usuários por role
            $roles = ['clienteapi', 'solicitante', 'pesquisador'];
            foreach ($roles as $role) {
                $count = User::whereHas('roles', function ($query) use ($role) {
                    $query->where('name', $role);
                })->count();
                echo "<p>Usuários com role '{$role}': {$count}</p>";
            }
            
            // Usuários sem role apropriada
            $usuariosSemRole = User::where('status', User::STATUS['ATIVO'])
                ->whereDoesntHave('roles', function ($query) {
                    $query->whereIn('name', ['clienteapi', 'solicitante', 'pesquisador']);
                })
                ->count();
            echo "<p class='warning'>Usuários ativos sem role apropriada: {$usuariosSemRole}</p>";
            
            echo "<h2>💡 SOLUÇÕES</h2>";
            echo "<ol>";
            echo "<li>Criar usuários com roles corretas</li>";
            echo "<li>Ativar usuários existentes</li>";
            echo "<li>Atribuir roles apropriadas aos usuários</li>";
            echo "</ol>";
        }
        
        echo "<h2>🔧 CONFIGURAÇÃO DO CAMPO CLIENTE</h2>";
        echo "<p>O campo Cliente no formulário busca por:</p>";
        echo "<ul>";
        echo "<li>Usuários com status = " . User::STATUS['ATIVO'] . " (ATIVO)</li>";
        echo "<li>Usuários com roles: clienteapi, solicitante, pesquisador</li>";
        echo "<li>Ordenados por nome</li>";
        echo "</ul>";
        
    } catch (Exception $e) {
        echo "<p class='error'><strong>❌ ERRO:</strong> " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    ?>

    <hr>
    <p><small>🕒 Executado em: <?= date('d/m/Y H:i:s') ?></small></p>
    <p><small>📍 Para usar este debug, acesse: <code>https://novo.falecidosnobrasil.org.br/debug-clientes-producao.php</code></small></p>
</body>
</html> 