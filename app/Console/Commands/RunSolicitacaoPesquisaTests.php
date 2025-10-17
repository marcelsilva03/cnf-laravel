<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunSolicitacaoPesquisaTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:solicitacao-pesquisa 
                            {--coverage : Run with code coverage}
                            {--filter= : Run specific test method}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run E2E tests for Solicitação de Pesquisa de Falecido workflow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Running Solicitação de Pesquisa de Falecido E2E Tests...');
        $this->newLine();

        // Prepare test environment
        $this->info('📦 Preparing test environment...');
        
        // Refresh test database
        if ($this->confirm('Do you want to refresh the test database?', true)) {
            $this->call('migrate:fresh', [
                '--env' => 'testing',
                '--seed' => true,
            ]);
        }

        // Build test command
        $command = 'test';
        $options = [
            '--testsuite' => 'Feature',
        ];

        // Add filter if provided
        if ($filter = $this->option('filter')) {
            $options['--filter'] = $filter;
        } else {
            $options['--filter'] = 'SolicitacaoPesquisa';
        }

        // Add coverage if requested
        if ($this->option('coverage')) {
            $options['--coverage'] = true;
            $options['--coverage-html'] = 'coverage-report';
            $this->info('📊 Code coverage will be generated in coverage-report/');
        }

        $this->newLine();
        $this->info('🚀 Running tests...');
        $this->newLine();

        // Run tests
        $result = Artisan::call($command, $options);

        // Show results
        $this->newLine();
        if ($result === 0) {
            $this->info('✅ All tests passed successfully!');
            
            // Show test statistics
            $this->displayTestStatistics();
        } else {
            $this->error('❌ Some tests failed. Check the output above for details.');
        }

        return $result;
    }

    /**
     * Display test statistics
     */
    protected function displayTestStatistics()
    {
        $this->newLine();
        $this->table(
            ['Test Suite', 'Files'],
            [
                ['Main Workflow Tests', 'SolicitacaoPesquisaFalecidoE2ETest.php'],
                ['Edge Cases Tests', 'SolicitacaoPesquisaWorkflowEdgeCasesTest.php'],
            ]
        );

        $this->newLine();
        $this->info('📋 Test Coverage Areas:');
        $this->line('  • Complete workflow (search → payment → processing → completion)');
        $this->line('  • Falecido not found scenario');
        $this->line('  • Payment cancellation');
        $this->line('  • Error communication');
        $this->line('  • Multiple search results');
        $this->line('  • Invalid data validation');
        $this->line('  • Permission checks');
        $this->line('  • Concurrent operations');
        
        $this->newLine();
        $this->info('💡 Tips:');
        $this->line('  • Run with --coverage to see code coverage report');
        $this->line('  • Use --filter=test_method_name to run specific test');
        $this->line('  • Check logs in storage/logs/testing.log for details');
    }
}