<?php

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'];
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!\n";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    echo "DSN: " . $dsn . "\n";
    echo "Username: " . $_ENV['DB_USERNAME'] . "\n";
    echo "Database: " . $_ENV['DB_DATABASE'] . "\n";
} 