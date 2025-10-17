<?php

// Script de diagnóstico para verificar widgets registrados
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== DIAGNÓSTICO DE WIDGETS ===\n\n";

// Simular uma requisição para inicializar o Laravel
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Verificar se existe o arquivo Dashboard.php
$dashboardFile = __DIR__ . '/app/Filament/Pages/Dashboard.php';
if (file_exists($dashboardFile)) {
    echo "✅ Dashboard.php encontrado\n";
    
    // Ler o conteúdo do arquivo
    $content = file_get_contents($dashboardFile);
    
    // Verificar se ainda há referência ao FinanceiroSimplificadoWidget
    if (strpos($content, 'FinanceiroSimplificadoWidget') !== false) {
        echo "❌ PROBLEMA: FinanceiroSimplificadoWidget ainda referenciado no Dashboard.php!\n";
        
        // Mostrar as linhas que contêm a referência
        $lines = explode("\n", $content);
        foreach ($lines as $lineNum => $line) {
            if (strpos($line, 'FinanceiroSimplificadoWidget') !== false) {
                echo "   Linha " . ($lineNum + 1) . ": " . trim($line) . "\n";
            }
        }
    } else {
        echo "✅ FinanceiroSimplificadoWidget não encontrado no Dashboard.php\n";
    }
    
    // Verificar widgets para perfil financeiro
    if (strpos($content, "hasRole('financeiro')") !== false) {
        echo "✅ Configuração para perfil financeiro encontrada\n";
        
        // Extrair a configuração do perfil financeiro
        preg_match("/hasRole\('financeiro'\)\) \{([^}]+)\}/", $content, $matches);
        if (isset($matches[1])) {
            echo "   Configuração atual: " . trim($matches[1]) . "\n";
        }
    }
} else {
    echo "❌ Dashboard.php não encontrado!\n";
}

// Verificar se o arquivo FinanceiroSimplificadoWidget ainda existe
$widgetFile = __DIR__ . '/app/Filament/Widgets/FinanceiroSimplificadoWidget.php';
if (file_exists($widgetFile)) {
    echo "❌ PROBLEMA: FinanceiroSimplificadoWidget.php ainda existe!\n";
    echo "   Arquivo: {$widgetFile}\n";
} else {
    echo "✅ FinanceiroSimplificadoWidget.php removido corretamente\n";
}

// Verificar componentes Livewire registrados
echo "\n=== COMPONENTES LIVEWIRE REGISTRADOS ===\n";
try {
    $livewire = app('livewire');
    echo "✅ Livewire carregado\n";
    
    // Tentar acessar o registry de componentes
    $registry = app(\Livewire\Mechanisms\ComponentRegistry::class);
    echo "✅ ComponentRegistry acessível\n";
    
} catch (Exception $e) {
    echo "❌ Erro ao acessar Livewire: " . $e->getMessage() . "\n";
}

// Verificar cache do Laravel
echo "\n=== VERIFICAÇÃO DE CACHE ===\n";
$cacheFiles = [
    'bootstrap/cache/config.php' => 'Cache de configuração',
    'bootstrap/cache/routes-v7.php' => 'Cache de rotas',
    'bootstrap/cache/services.php' => 'Cache de serviços',
    'storage/framework/views' => 'Cache de views',
    'storage/framework/cache' => 'Cache da aplicação',
];

foreach ($cacheFiles as $file => $description) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        if (is_dir($fullPath)) {
            $count = count(glob($fullPath . '/*'));
            echo "⚠️  {$description}: {$count} arquivos\n";
        } else {
            echo "⚠️  {$description}: existe\n";
        }
    } else {
        echo "✅ {$description}: limpo\n";
    }
}

echo "\n=== DIAGNÓSTICO CONCLUÍDO ===\n";
echo "Se ainda houver problemas, execute o script clear-all-cache.php\n"; 