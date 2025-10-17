<?php

try {
    // Step 1: Check autoloader
    echo "Step 1: Checking autoloader...\n";
    $autoloaderPath = __DIR__.'/../vendor/autoload.php';
    if (!file_exists($autoloaderPath)) {
        throw new Exception("Autoloader not found at: " . $autoloaderPath);
    }
    require $autoloaderPath;
    echo "Autoloader loaded successfully\n";

    // Step 2: Load application
    echo "\nStep 2: Loading application...\n";
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "Application loaded successfully\n";
    echo "Application base path: " . $app->basePath() . "\n";

    // Step 3: Initialize kernel
    echo "\nStep 3: Initializing kernel...\n";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "Kernel initialized successfully\n";

    // Step 4: Bootstrap application
    echo "\nStep 4: Bootstrapping application...\n";
    try {
        // Check if storage directory is writable
        $storagePath = $app->storagePath();
        if (!is_writable($storagePath)) {
            throw new Exception("Storage directory is not writable: " . $storagePath);
        }
        echo "Storage directory is writable\n";

        // Check if bootstrap/cache directory is writable
        $bootstrapCachePath = $app->bootstrapPath('cache');
        if (!is_writable($bootstrapCachePath)) {
            throw new Exception("Bootstrap cache directory is not writable: " . $bootstrapCachePath);
        }
        echo "Bootstrap cache directory is writable\n";

        // Try to bootstrap
        $kernel->bootstrap();
        echo "Application bootstrapped successfully\n";
    } catch (Exception $e) {
        echo "Error during bootstrap: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Line: " . $e->getLine() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
        throw $e;
    }

    // Step 5: Check service providers
    echo "\nStep 5: Checking service providers...\n";
    $providers = $app->config['app.providers'];
    echo "Configured providers:\n";
    foreach ($providers as $provider) {
        echo "- " . $provider . "\n";
    }

    // Step 6: Check environment
    echo "\nStep 6: Checking environment...\n";
    echo "Environment: " . $app->environment() . "\n";
    echo "Debug mode: " . ($app->hasDebugModeEnabled() ? 'Yes' : 'No') . "\n";
    echo "Base path: " . $app->basePath() . "\n";
    echo "Config path: " . $app->configPath() . "\n";
    echo "Storage path: " . $app->storagePath() . "\n";

    // Step 7: Check loaded providers
    echo "\nStep 7: Checking loaded providers...\n";
    $loadedProviders = array_keys($app->getLoadedProviders());
    echo "Loaded providers:\n";
    foreach ($loadedProviders as $provider) {
        echo "- " . $provider . "\n";
    }

    // Step 8: Check database connection
    echo "\nStep 8: Checking database connection...\n";
    $db = $app->make('db');
    $connection = $db->connection();
    echo "Database connected successfully\n";
    echo "Database name: " . $connection->getDatabaseName() . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 