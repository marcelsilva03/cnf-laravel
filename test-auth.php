<?php

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== TESTE DE AUTENTICAÇÃO CNF ===\n\n";

try {
    // Testar conexão com banco
    $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'];
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexão com banco: OK\n";
    
    // Verificar se tabela users existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabela 'users': Existe\n";
    } else {
        echo "❌ Tabela 'users': NÃO EXISTE\n";
        exit(1);
    }
    
    // Contar usuários
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "📊 Total de usuários: " . $result['total'] . "\n";
    
    // Listar usuários
    echo "\n=== USUÁRIOS CADASTRADOS ===\n";
    $stmt = $pdo->query("SELECT id, name, email, status, created_at FROM users ORDER BY id");
    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $status = $user['status'] == 1 ? 'ATIVO' : 'INATIVO';
        echo "ID: {$user['id']} | {$user['name']} | {$user['email']} | {$status}\n";
    }
    
    // Verificar tabela roles
    echo "\n=== VERIFICAÇÃO DE ROLES ===\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'roles'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabela 'roles': Existe\n";
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM roles");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📊 Total de roles: " . $result['total'] . "\n";
        
        // Listar roles
        $stmt = $pdo->query("SELECT id, name FROM roles ORDER BY id");
        while ($role = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- {$role['name']} (ID: {$role['id']})\n";
        }
    } else {
        echo "❌ Tabela 'roles': NÃO EXISTE\n";
    }
    
    // Verificar model_has_roles
    echo "\n=== VERIFICAÇÃO DE PERMISSÕES ===\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'model_has_roles'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabela 'model_has_roles': Existe\n";
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM model_has_roles");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📊 Total de atribuições de roles: " . $result['total'] . "\n";
    } else {
        echo "❌ Tabela 'model_has_roles': NÃO EXISTE\n";
    }
    
    // Testar credenciais específicas
    echo "\n=== TESTE DE CREDENCIAIS ===\n";
    $testCredentials = [
        ['email' => 'admin@email.com', 'password' => '123'],
        ['email' => 'admin@exemplo.com', 'password' => '123'],
        ['email' => 'proprietario@email.com', 'password' => '123'],
    ];
    
    foreach ($testCredentials as $cred) {
        $stmt = $pdo->prepare("SELECT id, name, email, password, status FROM users WHERE email = ?");
        $stmt->execute([$cred['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $passwordMatch = password_verify($cred['password'], $user['password']);
            $status = $user['status'] == 1 ? 'ATIVO' : 'INATIVO';
            echo "📧 {$cred['email']}: ";
            echo "Usuário encontrado | Status: {$status} | ";
            echo "Senha: " . ($passwordMatch ? "✅ CORRETA" : "❌ INCORRETA") . "\n";
        } else {
            echo "📧 {$cred['email']}: ❌ USUÁRIO NÃO ENCONTRADO\n";
        }
    }
    
    // Verificar migrações
    echo "\n=== VERIFICAÇÃO DE MIGRAÇÕES ===\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'migrations'");
    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM migrations");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📊 Migrações executadas: " . $result['total'] . "\n";
    } else {
        echo "❌ Tabela 'migrations': NÃO EXISTE\n";
    }
    
} catch(PDOException $e) {
    echo "❌ Erro de conexão: " . $e->getMessage() . "\n";
    echo "Host: " . $_ENV['DB_HOST'] . "\n";
    echo "Database: " . $_ENV['DB_DATABASE'] . "\n";
    echo "Username: " . $_ENV['DB_USERNAME'] . "\n";
}

echo "\n=== FIM DO TESTE ===\n"; 