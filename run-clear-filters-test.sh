#!/bin/bash

echo "=========================================="
echo "Running Clear Filters E2E Test Locally"
echo "=========================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the cnf-laravel directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: This script must be run from the cnf-laravel directory${NC}"
    exit 1
fi

# Check PHP version
echo "PHP Version: $(php -v | head -n 1)"
echo ""

# Method 1: Using SQLite (for local testing without database)
echo -e "${YELLOW}Method 1: Testing with SQLite (no database required)${NC}"
echo "Creating temporary .env.testing with SQLite..."

cat > .env.testing << EOF
APP_NAME="CNF Testing"
APP_ENV=testing
APP_KEY=base64:somerandomkeyfortesting123456789012345678=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

CACHE_DRIVER=array
SESSION_DRIVER=array
MAIL_MAILER=array
EOF

echo "Running test with SQLite..."
php artisan test tests/E2E/ProductionClearFiltersTest.php --env=testing

echo ""
echo -e "${YELLOW}Method 2: Testing with your local database${NC}"
echo "Using your existing .env configuration..."

# Run the test
echo "Running Clear Filters test..."
./vendor/bin/phpunit tests/E2E/ProductionClearFiltersTest.php --filter test_clear_filters_button_works_without_405_error

# Cleanup
rm -f .env.testing

echo ""
echo "=========================================="
echo "Test Complete!"
echo "=========================================="