#!/bin/bash

echo "=========================================="
echo "E2E Test Runner for Local Development"
echo "=========================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check PHP version
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
echo "PHP Version: $PHP_VERSION"

# Check if SQLite is available
if php -m | grep -q sqlite3; then
    echo -e "${GREEN}✓ SQLite3 extension found${NC}"
else
    echo -e "${RED}✗ SQLite3 extension not found${NC}"
    echo "Please install: brew install php@8.1-sqlite3"
    exit 1
fi

# Create .env.testing if it doesn't exist
if [ ! -f .env.testing ]; then
    echo -e "${YELLOW}Creating .env.testing file...${NC}"
    cat > .env.testing << EOL
APP_NAME="CNF Testing"
APP_ENV=testing
APP_KEY=base64:YourGeneratedKeyHere
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

BROADCAST_DRIVER=log
CACHE_DRIVER=array
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
SESSION_LIFETIME=120

MAIL_MAILER=array
EOL
    echo -e "${GREEN}✓ .env.testing created${NC}"
fi

# Generate app key if needed
echo "Generating application key..."
php artisan key:generate --env=testing

# Clear all caches
echo "Clearing caches..."
php artisan cache:clear --env=testing 2>/dev/null
php artisan config:clear --env=testing 2>/dev/null
php artisan route:clear --env=testing 2>/dev/null
php artisan view:clear --env=testing 2>/dev/null

echo ""
echo "Running E2E Tests..."
echo "===================="
echo ""

# Run the tests based on argument
case "$1" in
    "filter")
        echo "Running Clear Filters tests only..."
        php artisan test tests/E2E/FalecidosClearFiltersE2ETest.php --env=testing
        ;;
    "resource")
        echo "Running Resource tests only..."
        php artisan test tests/E2E/FalecidosResourceE2ETest.php --env=testing
        ;;
    "verbose")
        echo "Running all tests with verbose output..."
        php artisan test tests/E2E/ --env=testing -v
        ;;
    "single")
        if [ -z "$2" ]; then
            echo -e "${RED}Please specify test method name${NC}"
            echo "Usage: ./test-e2e-local.sh single test_method_name"
            exit 1
        fi
        echo "Running single test: $2"
        php artisan test --env=testing --filter="$2"
        ;;
    *)
        echo "Running all E2E tests..."
        php artisan test tests/E2E/ --env=testing
        ;;
esac

# Check exit code
if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}=========================================="
    echo "✓ E2E Tests Passed Successfully!"
    echo "==========================================${NC}"
else
    echo ""
    echo -e "${RED}=========================================="
    echo "✗ E2E Tests Failed!"
    echo "==========================================${NC}"
    exit 1
fi