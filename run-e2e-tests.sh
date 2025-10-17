#!/bin/bash

echo "=========================================="
echo "Running E2E Tests for Falecidos Resource"
echo "=========================================="
echo ""

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "Error: vendor directory not found. Please run 'composer install' first."
    exit 1
fi

# Clear cache before running tests
echo "Clearing cache..."
php artisan cache:clear > /dev/null 2>&1
php artisan config:clear > /dev/null 2>&1

# Run E2E tests
echo "Running E2E tests..."
echo ""

if [ "$1" == "filter" ]; then
    # Run only Clear Filters tests
    echo "Running Clear Filters E2E tests only..."
    php vendor/bin/phpunit --configuration phpunit.e2e.xml tests/E2E/FalecidosClearFiltersE2ETest.php
elif [ "$1" == "all" ]; then
    # Run all E2E tests
    echo "Running all E2E tests..."
    php vendor/bin/phpunit --configuration phpunit.e2e.xml
elif [ "$1" == "verbose" ]; then
    # Run with verbose output
    echo "Running E2E tests with verbose output..."
    php vendor/bin/phpunit --configuration phpunit.e2e.xml --verbose
else
    # Default: run all E2E tests with standard output
    php vendor/bin/phpunit --configuration phpunit.e2e.xml
fi

echo ""
echo "=========================================="
echo "E2E Tests Complete!"
echo "=========================================="