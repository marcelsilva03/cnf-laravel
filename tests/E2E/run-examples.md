# E2E Test Examples - How to Run

## Quick Start (Without Database)

Since you don't have a local database, the tests are configured to use SQLite in-memory database.

### 1. Basic Test Run
```bash
cd cnf-laravel

# Run all E2E tests
./test-e2e-local.sh

# Or using Laravel's test command
php artisan test tests/E2E/
```

### 2. Run Specific Test Scenarios

#### Test only the Clear Filters functionality:
```bash
./test-e2e-local.sh filter
```

#### Test only the main Resource tests:
```bash
./test-e2e-local.sh resource
```

#### Run with detailed output:
```bash
./test-e2e-local.sh verbose
```

#### Run a single test method:
```bash
# Test the specific Clear Filters button
./test-e2e-local.sh single test_clear_filters_button_removes_all_filters

# Test role-based access
./test-e2e-local.sh single test_role_based_access_control
```

## What Happens During Testing

### Step 1: Setup
- Creates in-memory SQLite database
- Runs migrations automatically
- Creates test users and roles
- Seeds test Falecidos data

### Step 2: Test Execution
Each test:
1. Simulates user login
2. Makes HTTP requests to endpoints
3. Verifies responses
4. Checks database changes

### Step 3: Cleanup
- Database is wiped after each test
- Ensures tests don't affect each other

## Example Test Flow

Here's what happens in the Clear Filters test:

```php
// 1. User visits page with filters
GET /admin/falecidos?tableFilters[search][value]=João

// 2. Test verifies filtered results
✓ Shows "João Silva"
✗ Doesn't show "Maria Santos"

// 3. User clicks Clear Filters (simulated by visiting URL without filters)
GET /admin/falecidos

// 4. Test verifies all records visible
✓ Shows "João Silva"
✓ Shows "Maria Santos"
✓ Shows all other records
```

## Manual Testing on Production

After deployment, test manually at https://novo.falecidosnobrasil.org.br:

1. **Login** with admin credentials
2. **Navigate** to Gestão de Óbitos > Falecidos
3. **Apply filters**:
   - Search: "Silva"
   - Status: Ativo
   - Date range: Last month
4. **Click** "Limpar filtros" button
5. **Verify**:
   - No 405 error
   - All filters removed
   - All records visible

## Common Test Commands

```bash
# List all available tests
php artisan test --list

# Run tests and stop on first failure
php artisan test --stop-on-failure

# Run tests in parallel (faster)
php artisan test --parallel

# See code coverage (requires XDEBUG)
php artisan test --coverage
```

## Interpreting Results

### Success:
```
PASS  Tests\E2E\FalecidosClearFiltersE2ETest
✓ clear filters removes search filter
✓ clear filters removes status filter
✓ clear filters removes date range filter

Tests:  3 passed
Time:   2.41s
```

### Failure:
```
FAIL  Tests\E2E\FalecidosClearFiltersE2ETest
✗ clear filters removes search filter

Expected response status code [200] but received 405.
Failed asserting that 405 is identical to 200.

Tests:  1 failed, 2 passed
```

## Quick Troubleshooting

### If tests fail to run:
```bash
# Clear everything and retry
php artisan cache:clear
php artisan config:clear
composer dump-autoload
```

### If you see database errors:
```bash
# Ensure SQLite is installed
php -m | grep sqlite

# Check .env.testing has:
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### If you see 404 errors:
```bash
# Routes might be cached
php artisan route:clear
php artisan route:cache
```