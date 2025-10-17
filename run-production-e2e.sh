#!/bin/bash

# Production E2E Test Runner
# This script runs E2E tests against the production database safely
# It creates a temporary test database and cleans up afterward

echo "=========================================="
echo "Production E2E Test Runner"
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

# Load production environment variables
if [ -f ".env" ]; then
    source .env
else
    echo -e "${RED}Error: .env file not found${NC}"
    exit 1
fi

# Create test database name
TEST_DB="${DB_DATABASE}_e2e_test_$(date +%s)"

echo -e "${YELLOW}Creating temporary test database: $TEST_DB${NC}"

# Create test database
mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $TEST_DB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if [ $? -ne 0 ]; then
    echo -e "${RED}Failed to create test database${NC}"
    exit 1
fi

# Create .env.e2e file
cat > .env.e2e << EOF
APP_NAME="${APP_NAME} E2E Test"
APP_ENV=testing
APP_KEY=$APP_KEY
APP_DEBUG=false
APP_URL=$APP_URL

LOG_CHANNEL=single
LOG_LEVEL=error

DB_CONNECTION=$DB_CONNECTION
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_DATABASE=$TEST_DB
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD

BROADCAST_DRIVER=log
CACHE_DRIVER=array
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
SESSION_LIFETIME=120

MAIL_MAILER=array
EOF

echo -e "${GREEN}✓ Test environment configured${NC}"

# Run migrations on test database
echo -e "${YELLOW}Running migrations on test database...${NC}"
php artisan migrate --env=e2e --force --quiet

if [ $? -ne 0 ]; then
    echo -e "${RED}Failed to run migrations${NC}"
    # Cleanup
    mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "DROP DATABASE IF EXISTS $TEST_DB;"
    rm -f .env.e2e
    exit 1
fi

# Run E2E tests
echo ""
echo -e "${YELLOW}Running E2E Tests...${NC}"
echo "===================="

# Run specific safe tests that won't affect production
php artisan test tests/E2E/FalecidosClearFiltersE2ETest.php --env=e2e \
    --filter "test_clear_filters_button_appears_in_empty_state|test_clear_filters_removes_search_filter|test_clear_filters_accessibility"

TEST_RESULT=$?

# Cleanup
echo ""
echo -e "${YELLOW}Cleaning up...${NC}"
mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "DROP DATABASE IF EXISTS $TEST_DB;"
rm -f .env.e2e

# Report results
if [ $TEST_RESULT -eq 0 ]; then
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