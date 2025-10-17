<?php

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    // First connect without database to check user
    $pdo = new PDO("mysql:host=" . $_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '" . $_ENV['DB_DATABASE'] . "'");
    if ($stmt->rowCount() > 0) {
        echo "Database '" . $_ENV['DB_DATABASE'] . "' exists\n";
    } else {
        echo "Database '" . $_ENV['DB_DATABASE'] . "' does not exist\n";
    }
    
    // Check user permissions
    $stmt = $pdo->query("SHOW GRANTS FOR CURRENT_USER()");
    echo "\nUser permissions:\n";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo $row[0] . "\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Host: " . $_ENV['DB_HOST'] . "\n";
    echo "Username: " . $_ENV['DB_USERNAME'] . "\n";
} 