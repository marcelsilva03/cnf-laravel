# E2E Tests for Solicitação de Pesquisa de Falecido

## Overview
These tests validate the complete workflow for "Solicitação de Pesquisa de Falecido" as shown in the process diagram.

## Test Coverage

### 1. Main Workflow Test (`SolicitacaoPesquisaFalecidoE2ETest.php`)

#### `test_complete_workflow_solicitacao_pesquisa_falecido`
- **Purpose**: Tests the happy path from search to completion
- **Steps**:
  1. User searches for deceased person
  2. System finds the record
  3. User proceeds with search request
  4. User makes payment
  5. Pesquisador processes the request
  6. Pesquisador updates with registry office data
  7. System notifies user of completion

#### `test_falecido_not_found_creates_comunicado_obito`
- **Purpose**: Tests the alternative flow when deceased is not found
- **Steps**:
  1. User searches for non-existent deceased
  2. System shows no results
  3. User proceeds to communicate death
  4. System creates new falecido record

#### `test_payment_cancellation_workflow`
- **Purpose**: Tests payment cancellation scenario
- **Validates**: Proper status change and user notification

#### `test_error_communication_workflow`
- **Purpose**: Tests error reporting in deceased data
- **Validates**: Error communication creation and tracking

### 2. Edge Cases Tests (`SolicitacaoPesquisaWorkflowEdgeCasesTest.php`)

#### Data Validation Tests
- `test_search_with_invalid_document_formats`: CPF/RG format validation
- `test_death_date_cannot_be_future`: Date logic validation
- `test_search_handles_special_characters`: Special characters in names

#### System Integration Tests
- `test_localidades_service_integration`: Dynamic city/state loading
- `test_cartorio_not_in_system_manual_entry`: Manual cartório entry
- `test_prevent_duplicate_payment_processing`: Payment idempotency

#### Multi-record Scenarios
- `test_search_returns_multiple_results_with_same_name`: Disambiguation
- `test_pesquisador_bulk_update_solicitacoes`: Bulk operations

## Running the Tests

### Run all E2E tests:
```bash
php artisan test --testsuite=Feature --filter=SolicitacaoPesquisa
```

### Run specific test class:
```bash
php artisan test tests/Feature/SolicitacaoPesquisaFalecidoE2ETest.php
```

### Run with coverage:
```bash
php artisan test --coverage --testsuite=Feature --filter=SolicitacaoPesquisa
```

## Test Data Setup

### Required Seeders
- UserSeeder (with roles: admin, pesquisador, user)
- CartorioSeeder (at least one test cartório)
- LocalidadesConfig (estados and cidades)

### Factory States
```php
// Create test falecido with complete data
Falecido::factory()->complete()->create();

// Create falecido without cartório data
Falecido::factory()->withoutCartorio()->create();

// Create paid solicitação
Solicitacao::factory()->paid()->create();
```

## Environment Variables for Testing
```env
# Test database
DB_CONNECTION=mysql
DB_DATABASE=novo_cnf_test

# Payment gateway test mode
PAYMENT_TEST_MODE=true
PAYMENT_WEBHOOK_SECRET=test_secret

# Email testing
MAIL_MAILER=array
```

## Assertions Checklist

### Database Assertions
- [ ] Falecido record created/updated correctly
- [ ] Solicitação status transitions
- [ ] Payment records
- [ ] Comunicado de óbito records
- [ ] Error communications

### Response Assertions
- [ ] Correct HTTP status codes
- [ ] Proper redirects
- [ ] Session messages
- [ ] View data presence

### Business Logic Assertions
- [ ] Status workflow follows diagram
- [ ] Permissions correctly enforced
- [ ] Email notifications sent
- [ ] Payment processing logic

## Known Test Scenarios from Migration

### Data Migration Validation
The migration script handles:
- UUID generation for falecidos
- CPF/RG separation and cleaning
- Date format conversion (YYYYMMDD to YYYY-MM-DD)
- Timestamp preservation for burial date
- Cartório association (fal_id_ccc)

### Fields Mapped in Migration
- Personal data: nome, cpf, rg, sexo, data_nascimento
- Death data: data_falecimento, local, cartório info
- Registry data: livro, folha, termo, declaração
- Location: uf, cidade

## CI/CD Integration

Add to `.gitlab-ci.yml` or GitHub Actions:
```yaml
test:e2e:
  script:
    - php artisan migrate:fresh --env=testing
    - php artisan db:seed --class=TestDataSeeder --env=testing
    - php artisan test --testsuite=Feature --filter=SolicitacaoPesquisa
```