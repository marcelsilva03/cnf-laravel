# E2E Testing Guide for Falecidos Resource

## Prerequisites

### Option 1: Using SQLite (Recommended for local testing)
```bash
# Install SQLite PHP extension if not already installed
# On macOS:
brew install sqlite3
brew install php@8.1-sqlite3

# On Ubuntu/Debian:
sudo apt-get install php8.1-sqlite3
```

### Option 2: Using MySQL Test Database
Create a test database:
```sql
CREATE DATABASE cnf_test;
```

Create `.env.testing` file:
```env
APP_ENV=testing
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cnf_test
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Running the Tests

### Method 1: Using the Shell Script (Easiest)
```bash
# Run all E2E tests
./run-e2e-tests.sh

# Run only Clear Filters tests
./run-e2e-tests.sh filter

# Run with verbose output
./run-e2e-tests.sh verbose
```

### Method 2: Using PHPUnit Directly
```bash
# Run all E2E tests
php vendor/bin/phpunit --configuration phpunit.e2e.xml

# Run specific test file
php vendor/bin/phpunit tests/E2E/FalecidosResourceE2ETest.php

# Run specific test method
php vendor/bin/phpunit --filter test_clear_filters_button_removes_all_filters
```

### Method 3: Using Laravel Artisan
```bash
# Run all E2E tests
php artisan test tests/E2E/

# Run with specific database
php artisan test tests/E2E/ --env=testing
```

## What the Tests Do

### 1. Database Setup
- Uses `RefreshDatabase` trait - creates fresh database for each test
- Seeds test data automatically
- Creates users with different roles (admin, moderador, pesquisador)
- Creates test Falecidos records

### 2. Simulated User Actions
The tests simulate real browser interactions:
```php
// Example: User applies filter and clears it
$response = $this->actingAs($admin)
    ->get('/admin/falecidos?tableFilters[search][value]=João');
    
// Verify filtered results
$response->assertSee('João Silva');
$response->assertDontSee('Maria Santos');

// Simulate clicking Clear Filters
$clearResponse = $this->actingAs($admin)
    ->get('/admin/falecidos');
    
// Verify all records visible again
$clearResponse->assertSee('João Silva');
$clearResponse->assertSee('Maria Santos');
```

### 3. Test Coverage Areas

#### FalecidosResourceE2ETest.php covers:
- Complete CRUD operations
- All filter types (search, status, date range)
- Role-based permissions
- Pagination
- Empty states
- Combined filters

#### FalecidosClearFiltersE2ETest.php focuses on:
- Clear Filters button in different scenarios
- URL handling
- Performance with complex filters
- Accessibility

## Interpreting Test Results

### Success Output:
```
PHPUnit 10.5.46 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.32
Configuration: /path/to/phpunit.e2e.xml

................................................................. 65 / 65 (100%)

Time: 00:12.345, Memory: 128.00 MB

OK (65 tests, 245 assertions)
```

### Failure Output:
```
FAILURES!
Tests: 65, Assertions: 245, Failures: 1.

1) Tests\E2E\FalecidosResourceE2ETest::test_clear_filters_button_removes_all_filters
Failed asserting that page contains "Limpar filtros".
```

## Manual Testing After Deployment

After the pipeline deploys to https://novo.falecidosnobrasil.org.br:

### 1. Test Clear Filters Button:
1. Login as admin
2. Go to `/admin/falecidos`
3. Apply search filter: search for "Silva"
4. Click "Limpar filtros" button
5. Verify: No 405 error, all records visible

### 2. Test Multiple Filters:
1. Apply search: "João"
2. Apply status: "Active"
3. Apply date range: Last month
4. Click "Limpar filtros"
5. Verify all filters removed at once

### 3. Test Empty State:
1. Search for: "NonExistentPerson123"
2. See empty state message
3. Click "Limpar filtros"
4. Verify records appear

### 4. Test Different Roles:
1. Login as pesquisador
2. Verify can view but not edit
3. Login as moderador
4. Verify can edit records

## Debugging Failed Tests

### 1. Check Test Logs:
```bash
# Run with detailed output
php vendor/bin/phpunit tests/E2E/FalecidosResourceE2ETest.php --debug
```

### 2. Dump Response Content:
```php
// Add to test to see actual response
$response = $this->get('/admin/falecidos');
dd($response->getContent()); // Shows HTML response
```

### 3. Check Database State:
```php
// Add to test to verify data
$falecidos = Falecido::all();
dd($falecidos->toArray());
```

## CI/CD Integration

To run tests in the pipeline, add to `.gitlab-ci.yml` or equivalent:
```yaml
test:e2e:
  stage: test
  script:
    - cp .env.testing .env
    - php artisan key:generate
    - php artisan migrate --env=testing
    - ./run-e2e-tests.sh
```

## Performance Considerations

- Tests use in-memory SQLite by default (fast)
- Each test refreshes database (ensures isolation)
- Run in parallel if needed: `php artisan test --parallel`

## Troubleshooting

### Common Issues:

1. **Database Connection Refused**
   - Solution: Use SQLite or ensure MySQL is running
   
2. **Permission Denied on Shell Script**
   - Solution: `chmod +x run-e2e-tests.sh`
   
3. **Class Not Found**
   - Solution: `composer dump-autoload`
   
4. **Routes Not Found**
   - Solution: `php artisan route:clear`