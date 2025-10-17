<?php

namespace App\Console\Commands;

use App\Models\Plano;
use Illuminate\Console\Command;

class ValidarPlanosCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'planos:validar 
                            {--fix : Tentar corrigir problemas automaticamente}
                            {--detailed : Mostrar detalhes completos}';

    /**
     * The console command description.
     */
    protected $description = 'Valida a integridade dos planos financeiros do sistema CNF';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Iniciando validação dos planos financeiros...');
        $this->newLine();

        $planos = Plano::orderBy('faixa_inicial')->get();
        
        if ($planos->isEmpty()) {
            $this->error('❌ Nenhum plano encontrado no sistema!');
            return 1;
        }

        $problemas = [];
        $warnings = [];
        
        // Verificar cada plano individualmente
        foreach ($planos as $plano) {
            $this->validarPlanoIndividual($plano, $problemas, $warnings);
        }

        // Verificar sobreposições entre planos
        $this->validarSobreposicoes($planos, $problemas);
        
        // Verificar lacunas na cobertura
        $this->validarLacunas($planos, $warnings);

        // Exibir resultados
        $this->exibirResultados($planos, $problemas, $warnings);

        // Aplicar correções se solicitado
        if ($this->option('fix') && !empty($problemas)) {
            $this->aplicarCorrecoes($problemas);
        }

        return empty($problemas) ? 0 : 1;
    }

    protected function validarPlanoIndividual(Plano $plano, array &$problemas, array &$warnings): void
    {
        $planoId = $plano->id;

        // Validação A: Faixa inicial > faixa final
        if ($plano->faixa_final !== null && $plano->faixa_inicial > $plano->faixa_final) {
            $problemas[] = [
                'tipo' => 'REGRA_A',
                'plano_id' => $planoId,
                'mensagem' => "Plano #{$planoId}: Faixa inicial ({$plano->faixa_inicial}) maior que faixa final ({$plano->faixa_final})",
                'severidade' => 'ERROR'
            ];
        }

        // Validação B: Valores negativos
        if ($plano->faixa_inicial < 0) {
            $problemas[] = [
                'tipo' => 'REGRA_B',
                'plano_id' => $planoId,
                'mensagem' => "Plano #{$planoId}: Faixa inicial negativa ({$plano->faixa_inicial})",
                'severidade' => 'ERROR'
            ];
        }

        if ($plano->faixa_final !== null && $plano->faixa_final < 0) {
            $problemas[] = [
                'tipo' => 'REGRA_B',
                'plano_id' => $planoId,
                'mensagem' => "Plano #{$planoId}: Faixa final negativa ({$plano->faixa_final})",
                'severidade' => 'ERROR'
            ];
        }

        // Validações de integridade adiccionais
        if ($plano->preco_por_consulta < 0) {
            $warnings[] = [
                'tipo' => 'PRECO_NEGATIVO',
                'plano_id' => $planoId,
                'mensagem' => "Plano #{$planoId}: Preço por consulta negativo ({$plano->preco_por_consulta})",
                'severidade' => 'WARNING'
            ];
        }
    }

    protected function validarSobreposicoes(object $planos, array &$problemas): void
    {
        $planosArray = $planos->toArray();
        
        for ($i = 0; $i < count($planosArray); $i++) {
            for ($j = $i + 1; $j < count($planosArray); $j++) {
                $plano1 = $planosArray[$i];
                $plano2 = $planosArray[$j];

                // Validação C: Duplicação exata
                if ($plano1['faixa_inicial'] === $plano2['faixa_inicial']) {
                    $problemas[] = [
                        'tipo' => 'REGRA_C',
                        'plano_id' => $plano1['id'],
                        'plano_id2' => $plano2['id'],
                        'mensagem' => "Planos #{$plano1['id']} e #{$plano2['id']}: Faixa inicial duplicada ({$plano1['faixa_inicial']})",
                        'severidade' => 'ERROR'
                    ];
                }

                if ($plano1['faixa_final'] !== null && $plano2['faixa_final'] !== null && 
                    $plano1['faixa_final'] === $plano2['faixa_final']) {
                    $problemas[] = [
                        'tipo' => 'REGRA_C',
                        'plano_id' => $plano1['id'],
                        'plano_id2' => $plano2['id'],
                        'mensagem' => "Planos #{$plano1['id']} e #{$plano2['id']}: Faixa final duplicada ({$plano1['faixa_final']})",
                        'severidade' => 'ERROR'
                    ];
                }

                // Validação D: Sobreposição
                if ($this->verificarSobreposicao($plano1, $plano2)) {
                    $faixa1 = $this->formatarFaixa($plano1);
                    $faixa2 = $this->formatarFaixa($plano2);
                    
                    $problemas[] = [
                        'tipo' => 'REGRA_D',
                        'plano_id' => $plano1['id'],
                        'plano_id2' => $plano2['id'],
                        'mensagem' => "Planos #{$plano1['id']} ({$faixa1}) e #{$plano2['id']} ({$faixa2}): Sobreposição de faixas",
                        'severidade' => 'ERROR'
                    ];
                }
            }
        }
    }

    protected function verificarSobreposicao(array $plano1, array $plano2): bool
    {
        $inicio1 = $plano1['faixa_inicial'];
        $fim1 = $plano1['faixa_final'] ?? PHP_INT_MAX;
        
        $inicio2 = $plano2['faixa_inicial'];
        $fim2 = $plano2['faixa_final'] ?? PHP_INT_MAX;

        // Verificar se há sobreposição
        return !($fim1 < $inicio2 || $fim2 < $inicio1);
    }

    protected function validarLacunas(object $planos, array &$warnings): void
    {
        $planosOrdenados = $planos->sortBy('faixa_inicial')->values();
        
        for ($i = 0; $i < count($planosOrdenados) - 1; $i++) {
            $planoAtual = $planosOrdenados[$i];
            $proximoPlano = $planosOrdenados[$i + 1];

            if ($planoAtual->faixa_final !== null) {
                $fimAtual = $planoAtual->faixa_final;
                $inicioProximo = $proximoPlano->faixa_inicial;

                if ($fimAtual + 1 < $inicioProximo) {
                    $lacunaInicio = $fimAtual + 1;
                    $lacunaFim = $inicioProximo - 1;
                    
                    $warnings[] = [
                        'tipo' => 'LACUNA',
                        'plano_id' => $planoAtual->id,
                        'mensagem' => "Lacuna detectada entre planos #{$planoAtual->id} e #{$proximoPlano->id}: faixa {$lacunaInicio} - {$lacunaFim} não coberta",
                        'severidade' => 'WARNING'
                    ];
                }
            }
        }
    }

    protected function exibirResultados(object $planos, array $problemas, array $warnings): void
    {
        $this->info("📊 Resumo da validação:");
        $this->table(['Métrica', 'Valor'], [
            ['Total de planos', $planos->count()],
            ['Planos ativos', $planos->where('ativo', true)->count()],
            ['Planos inativos', $planos->where('ativo', false)->count()],
            ['Problemas críticos', count($problemas)],
            ['Avisos', count($warnings)],
        ]);

        if (!empty($problemas)) {
            $this->newLine();
            $this->error('❌ PROBLEMAS CRÍTICOS ENCONTRADOS:');
            foreach ($problemas as $problema) {
                $this->line("  • {$problema['mensagem']}");
            }
        }

        if (!empty($warnings)) {
            $this->newLine();
            $this->warn('⚠️  AVISOS:');
            foreach ($warnings as $warning) {
                $this->line("  • {$warning['mensagem']}");
            }
        }

        if (empty($problemas) && empty($warnings)) {
            $this->newLine();
            $this->info('✅ Todos os planos estão íntegros!');
        }

        if ($this->option('detailed')) {
            $this->exibirDetalhesPlanos($planos);
        }
    }

    protected function exibirDetalhesPlanos(object $planos): void
    {
        $this->newLine();
        $this->info('📋 Detalhes dos planos:');
        
        $tableData = [];
        foreach ($planos as $plano) {
            $tableData[] = [
                $plano->id,
                number_format($plano->faixa_inicial, 0, ',', '.'),
                $plano->faixa_final ? number_format($plano->faixa_final, 0, ',', '.') : 'Ilimitada',
                'R$ ' . number_format($plano->preco_por_consulta, 4, ',', '.'),
                $plano->ativo ? '✅' : '❌',
            ];
        }

        $this->table([
            'ID', 'Faixa Inicial', 'Faixa Final', 'Preço/Consulta', 'Ativo'
        ], $tableData);
    }

    protected function aplicarCorrecoes(array $problemas): void
    {
        if (!$this->confirm('Deseja tentar aplicar correções automáticas?')) {
            return;
        }

        $this->info('🔧 Aplicando correções...');
        
        $corrigidos = 0;
        foreach ($problemas as $problema) {
            if ($this->aplicarCorrecaoIndividual($problema)) {
                $corrigidos++;
            }
        }

        $this->info("✅ {$corrigidos} problema(s) corrigido(s) automaticamente.");
        
        if ($corrigidos < count($problemas)) {
            $restantes = count($problemas) - $corrigidos;
            $this->warn("⚠️  {$restantes} problema(s) requerem correção manual.");
        }
    }

    protected function aplicarCorrecaoIndividual(array $problema): bool
    {
        switch ($problema['tipo']) {
            case 'REGRA_A':
                // Corrigir faixa inicial > faixa final
                $plano = Plano::find($problema['plano_id']);
                if ($plano && $plano->faixa_final !== null) {
                    $novaFaixaFinal = $plano->faixa_inicial + 999; // Adicionar margem
                    $plano->faixa_final = $novaFaixaFinal;
                    $plano->save();
                    $this->line("  ✓ Corrigido plano #{$plano->id}: faixa final ajustada para {$novaFaixaFinal}");
                    return true;
                }
                break;
                
            case 'REGRA_B':
                // Corrigir valores negativos
                $plano = Plano::find($problema['plano_id']);
                if ($plano) {
                    if ($plano->faixa_inicial < 0) {
                        $plano->faixa_inicial = 0;
                    }
                    if ($plano->faixa_final !== null && $plano->faixa_final < 0) {
                        $plano->faixa_final = null;
                    }
                    $plano->save();
                    $this->line("  ✓ Corrigido plano #{$plano->id}: valores negativos ajustados");
                    return true;
                }
                break;
        }
        
        return false;
    }

    protected function formatarFaixa(array $plano): string
    {
        $inicio = number_format($plano['faixa_inicial'], 0, ',', '.');
        $fim = $plano['faixa_final'] ? number_format($plano['faixa_final'], 0, ',', '.') : '∞';
        return "{$inicio} - {$fim}";
    }
} 