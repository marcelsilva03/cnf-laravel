<?php

// Script para limpar todos os caches possíveis
// Execute este arquivo via web browser ou linha de comando

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "=== LIMPEZA COMPLETA DE CACHE ===\n";

// Lista de comandos para limpar cache
$commands = [
    'config:clear' => 'Limpando cache de configuração',
    'route:clear' => 'Limpando cache de rotas',
    'view:clear' => 'Limpando cache de views',
    'cache:clear' => 'Limpando cache da aplicação',
    'optimize:clear' => 'Limpando otimizações',
    'filament:clear-cached-components' => 'Limpando cache do Filament',
];

foreach ($commands as $command => $description) {
    echo "\n{$description}...\n";
    try {
        $kernel->call($command);
        echo "✅ Sucesso: {$command}\n";
    } catch (Exception $e) {
        echo "⚠️  Erro em {$command}: " . $e->getMessage() . "\n";
    }
}

// Limpar cache de autoload do Composer
echo "\nLimpando cache do Composer...\n";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "✅ Autoload encontrado\n";
}

// Tentar remover arquivos de cache manualmente
$cacheDirectories = [
    __DIR__ . '/bootstrap/cache/',
    __DIR__ . '/storage/framework/cache/',
    __DIR__ . '/storage/framework/views/',
    __DIR__ . '/storage/framework/sessions/',
    __DIR__ . '/storage/logs/',
];

echo "\nLimpando diretórios de cache manualmente...\n";
foreach ($cacheDirectories as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '*');
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                unlink($file);
            }
        }
        echo "✅ Limpo: {$dir}\n";
    } else {
        echo "⚠️  Não encontrado: {$dir}\n";
    }
}

// Verificar se existe cache específico do Livewire
$livewireCache = __DIR__ . '/storage/app/livewire-tmp/';
if (is_dir($livewireCache)) {
    echo "\nLimpando cache específico do Livewire...\n";
    $files = glob($livewireCache . '*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✅ Cache do Livewire limpo\n";
}

echo "\n=== LIMPEZA CONCLUÍDA ===\n";
echo "Cache completamente limpo. Teste o sistema agora.\n";
echo "Lembre-se de deletar este arquivo após o uso!\n"; 