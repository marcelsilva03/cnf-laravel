# E2E Testing Guide

## Overview

This guide explains how to create and run End-to-End (E2E) tests in the CNF Laravel application. All E2E tests are automatically discovered and executed by the pipeline when placed in the `tests/E2E` directory.

## Directory Structure

```
tests/
└── E2E/
    ├── E2E_TESTING_GUIDE.md (this file)
    ├── README.md
    ├── TESTING_GUIDE.md
    └── *Test.php (all E2E test files)
```

## Creating New E2E Tests

### 1. File Naming Convention

All E2E test files must:
- Be placed in the `tests/E2E` directory
- End with `Test.php` suffix
- Follow PascalCase naming

Examples:
- `UserLoginE2ETest.php`
- `FalecidosSearchTest.php`
- `PaymentFlowTest.php`

### 2. Test Class Structure

```php
<?php

namespace Tests\E2E;

use App\Models\User;
use Tests\TestCase;

class YourFeatureE2ETest extends TestCase
{
    /**
     * Test description
     */
    public function test_feature_works_correctly()
    {
        // Test implementation
    }
}
```

### 3. Best Practices for E2E Tests

1. **Isolation**: Each test should be independent and not rely on other tests
2. **Database**: Use database transactions or refresh the database between tests
3. **Authentication**: Use `actingAs()` for authenticated requests
4. **Assertions**: Focus on user-facing functionality and business logic
5. **Production Safety**: Ensure tests don't modify production data

### 4. Example E2E Test

```php
<?php

namespace Tests\E2E;

use App\Models\User;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class HomenagemCreationE2ETest extends TestCase
{
    /**
     * Test that authenticated users can create homenagens
     */
    public function test_authenticated_user_can_create_homenagem()
    {
        // Arrange
        $user = User::factory()->create();
        $user->assignRole('pesquisador');
        
        // Act
        $response = $this->actingAs($user)
            ->post('/homenagem/nova', [
                'nome_falecido' => 'João Silva',
                'mensagem' => 'Descanse em paz',
                'autor' => 'Maria Silva'
            ]);
        
        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('homenagens', [
            'nome_falecido' => 'João Silva',
            'autor' => 'Maria Silva'
        ]);
    }
}
```

## Running E2E Tests

### Locally

Run all E2E tests:
```bash
./run-e2e-tests.sh
```

Run using PHPUnit directly:
```bash
./vendor/bin/phpunit --configuration phpunit-e2e.xml --testdox
```

Run a specific test:
```bash
./vendor/bin/phpunit tests/E2E/YourSpecificTest.php
```

### In Pipeline

The pipeline automatically runs all tests in the `tests/E2E` directory when code is pushed to the main branch. The pipeline:

1. Installs dev dependencies
2. Runs all E2E tests using `phpunit-e2e.xml` configuration
3. Cleans up dev dependencies
4. Reports success or failure

## Configuration

The E2E tests use a dedicated PHPUnit configuration file: `phpunit-e2e.xml`

Key settings:
- Test suite: `tests/E2E` directory
- Environment: `testing`
- Database: In-memory or test database
- Cache/Session: Array drivers for speed

## Tips for Writing Effective E2E Tests

1. **Test User Journeys**: Focus on complete user workflows
   ```php
   public function test_user_can_complete_payment_flow()
   {
       // Login
       // Select service
       // Fill payment details
       // Confirm payment
       // Check success
   }
   ```

2. **Use Descriptive Names**: Test names should clearly indicate what is being tested
   ```php
   public function test_admin_can_approve_pending_obituaries()
   public function test_guest_cannot_access_admin_panel()
   ```

3. **Test Edge Cases**: Include tests for error scenarios
   ```php
   public function test_payment_fails_with_invalid_card()
   public function test_search_returns_empty_for_nonexistent_cpf()
   ```

4. **Performance Considerations**: E2E tests can be slow, so:
   - Use factories for test data creation
   - Avoid unnecessary API calls
   - Use database transactions when possible

## Common Patterns

### Authentication Testing
```php
$admin = User::factory()->create();
$admin->assignRole('admin');

$response = $this->actingAs($admin)
    ->get('/admin/dashboard');

$response->assertStatus(200);
```

### Form Submission Testing
```php
$response = $this->post('/contact', [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'message' => 'Test message'
]);

$response->assertRedirect('/thank-you');
```

### API Testing
```php
$response = $this->withHeaders([
    'Authorization' => 'Bearer ' . $token,
])->json('POST', '/api/search', [
    'cpf' => '12345678900'
]);

$response->assertStatus(200)
    ->assertJsonStructure(['data', 'status']);
```

## Troubleshooting

1. **Test fails locally but passes in pipeline**: Check environment differences
2. **Database errors**: Ensure migrations are up to date
3. **Permission errors**: Verify roles and permissions are seeded correctly
4. **Timeout errors**: Consider breaking large tests into smaller ones

## Adding Your Test to the Suite

1. Create your test file in `tests/E2E/`
2. Ensure it follows the naming convention (`*Test.php`)
3. Run locally to verify it works
4. Commit and push - the pipeline will automatically include it

That's it! Your test will now run automatically in the pipeline along with all other E2E tests.