<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Falecido;
use App\Models\Homenagem;
use App\Models\ComunicadoDeErro;
use App\Models\ComunicadoDeObito;
use App\Models\Faturamento;
use App\Models\Solicitacao;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardIndicadoresWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['admin', 'proprietario', 'socio-gestor']);
    }

    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $startOfYear = $now->copy()->startOfYear();

        // 1. INDICADORES DE FALECIDOS
        $totalFalecidos = Falecido::where('fal_status', Falecido::STATUS['ATIVO'])->count();
        $falecidosPendentesModeracao = Falecido::where('fal_status', Falecido::STATUS['INATIVO'])->count();
        $falecidosEsteMes = Falecido::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // 2. INDICADORES DE COMUNICADOS DE ERRO
        $totalComunicadosErro = ComunicadoDeErro::count();
        $comunicadosErroTratados = ComunicadoDeErro::whereIn('status', [
            ComunicadoDeErro::STATUS['APROVADO'], 
            ComunicadoDeErro::STATUS['REJEITADO']
        ])->count();
        $comunicadosErroPendentes = ComunicadoDeErro::where('status', ComunicadoDeErro::STATUS['PENDENTE'])->count();
        $comunicadosErroEsteMes = ComunicadoDeErro::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // 3. INDICADORES DE COMUNICADOS DE ÓBITO
        $totalComunicadosObito = ComunicadoDeObito::count();
        $comunicadosObitoTratados = ComunicadoDeObito::whereIn('status', [
            ComunicadoDeObito::STATUS['APROVADO'], 
            ComunicadoDeObito::STATUS['REJEITADO']
        ])->count();
        $comunicadosObitoPendentes = ComunicadoDeObito::where('status', ComunicadoDeObito::STATUS['PENDENTE'])->count();
        $comunicadosObitoEsteMes = ComunicadoDeObito::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // 4. INDICADORES DE HOMENAGENS
        $totalHomenagens = Homenagem::count();
        $homenagensPublicadas = Homenagem::where('hom_status', Homenagem::STATUS['PUBLICADO'])->count();
        $homenagensModeracao = Homenagem::where('hom_status', Homenagem::STATUS['PENDENTE'])->count();
        $homenagensEsteMes = Homenagem::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // 5. INDICADORES DE FATURAMENTO
        $receitaTotalGerada = Faturamento::where('status', 'concluido')->sum('valor');
        $receitaEsteMes = Faturamento::where('status', 'concluido')
            ->whereBetween('data_pagamento', [$startOfMonth, $endOfMonth])
            ->sum('valor');
        $receitaEsteAno = Faturamento::where('status', 'concluido')
            ->whereBetween('data_pagamento', [$startOfYear, $now])
            ->sum('valor');
        $receitaPendente = Faturamento::where('status', 'pendente')->sum('valor');
        $faturamentosEsteMes = Faturamento::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // 6. INDICADORES EXTRAS (SOLICITAÇÕES)
        $solicitacoesPendentes = Solicitacao::where('status', Solicitacao::STATUS['PENDENTE'])->count();
        $solicitacoesAprovadas = Solicitacao::where('status', Solicitacao::STATUS['PAGA'])->count();

        return [
            // === SEÇÃO: FALECIDOS ===
            Stat::make('Total de Falecidos', number_format($totalFalecidos, 0, ',', '.'))
                ->description('Registros ativos no sistema')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),

            Stat::make('Falecidos Pendentes', $falecidosPendentesModeracao)
                ->description('Aguardando moderação para entrar no sistema')
                ->descriptionIcon('heroicon-m-clock')
                ->color($falecidosPendentesModeracao > 0 ? 'warning' : 'success')
                ->extraAttributes(['style' => $falecidosPendentesModeracao > 0 ? 'background-color: rgb(245 158 11 / 0.1)' : 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Novos Falecidos (Mês)', $falecidosEsteMes)
                ->description('Adicionados este mês')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('info')
                ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),

            // === SEÇÃO: COMUNICADOS DE ERRO ===
            Stat::make('Total Comunicados Erro', $totalComunicadosErro)
                ->description("Tratados: {$comunicadosErroTratados} | Pendentes: {$comunicadosErroPendentes}")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($comunicadosErroPendentes > 50 ? 'danger' : ($comunicadosErroPendentes > 20 ? 'warning' : 'success'))
                ->extraAttributes(['style' => $comunicadosErroPendentes > 50 ? 'background-color: rgb(239 68 68 / 0.1)' : ($comunicadosErroPendentes > 20 ? 'background-color: rgb(245 158 11 / 0.1)' : 'background-color: rgb(34 197 94 / 0.1)')]),

            Stat::make('Erros Pendentes Ação', $comunicadosErroPendentes)
                ->description('Necessitam moderação urgente')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($comunicadosErroPendentes > 0 ? 'danger' : 'success')
                ->extraAttributes(['style' => $comunicadosErroPendentes > 0 ? 'background-color: rgb(239 68 68 / 0.1)' : 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Novos Erros (Mês)', $comunicadosErroEsteMes)
                ->description('Comunicados recebidos este mês')
                ->descriptionIcon('heroicon-m-document-plus')
                ->color('gray')
                ->extraAttributes(['style' => 'background-color: rgb(107 114 128 / 0.1)']),

            // === SEÇÃO: COMUNICADOS DE ÓBITO ===
            Stat::make('Total Comunicados Óbito', $totalComunicadosObito)
                ->description("Tratados: {$comunicadosObitoTratados} | Pendentes: {$comunicadosObitoPendentes}")
                ->descriptionIcon('heroicon-m-document-text')
                ->color($comunicadosObitoPendentes > 30 ? 'warning' : 'info')
                ->extraAttributes(['style' => $comunicadosObitoPendentes > 30 ? 'background-color: rgb(245 158 11 / 0.1)' : 'background-color: rgb(6 182 212 / 0.1)']),

            Stat::make('Óbitos Pendentes Ação', $comunicadosObitoPendentes)
                ->description('Aguardando processamento')
                ->descriptionIcon('heroicon-m-inbox')
                ->color($comunicadosObitoPendentes > 0 ? 'warning' : 'success')
                ->extraAttributes(['style' => $comunicadosObitoPendentes > 0 ? 'background-color: rgb(245 158 11 / 0.1)' : 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Novos Óbitos (Mês)', $comunicadosObitoEsteMes)
                ->description('Comunicados recebidos este mês')
                ->descriptionIcon('heroicon-m-document-plus')
                ->color('primary')
                ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),

            // === SEÇÃO: HOMENAGENS ===
            Stat::make('Total de Homenagens', number_format($totalHomenagens, 0, ',', '.'))
                ->description("Publicadas: {$homenagensPublicadas} | Em moderação: {$homenagensModeracao}")
                ->descriptionIcon('heroicon-m-heart')
                ->color('gray')
                ->extraAttributes(['style' => 'background-color: rgb(107 114 128 / 0.1)']),

            Stat::make('Homenagens (Mês)', $homenagensEsteMes)
                ->description('Criadas este mês')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Moderação Homenagens', $homenagensModeracao)
                ->description('Aguardando aprovação')
                ->descriptionIcon('heroicon-m-eye')
                ->color($homenagensModeracao > 0 ? 'warning' : 'success')
                ->extraAttributes(['style' => $homenagensModeracao > 0 ? 'background-color: rgb(245 158 11 / 0.1)' : 'background-color: rgb(34 197 94 / 0.1)']),

            // === SEÇÃO: FATURAMENTO ===
            Stat::make('Receita Total', 'R$ ' . number_format($receitaTotalGerada, 2, ',', '.'))
                ->description('Valor total já faturado')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Receita Este Mês', 'R$ ' . number_format($receitaEsteMes, 2, ',', '.'))
                ->description('Faturamento do mês atual')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Receita Este Ano', 'R$ ' . number_format($receitaEsteAno, 2, ',', '.'))
                ->description('Acumulado no ano')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info')
                ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),

            Stat::make('Receitas Pendentes', 'R$ ' . number_format($receitaPendente, 2, ',', '.'))
                ->description('Aguardando pagamento')
                ->descriptionIcon('heroicon-m-clock')
                ->color($receitaPendente > 10000 ? 'warning' : 'info')
                ->extraAttributes(['style' => $receitaPendente > 10000 ? 'background-color: rgb(245 158 11 / 0.1)' : 'background-color: rgb(6 182 212 / 0.1)']),

            // === SEÇÃO: RESUMO OPERACIONAL ===
            Stat::make('Solicitações Pendentes', $solicitacoesPendentes)
                ->description('Aguardando aprovação')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color($solicitacoesPendentes > 0 ? 'warning' : 'success')
                ->extraAttributes(['style' => $solicitacoesPendentes > 0 ? 'background-color: rgb(245 158 11 / 0.1)' : 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Faturamentos (Mês)', $faturamentosEsteMes)
                ->description('Novos faturamentos gerados')
                ->descriptionIcon('heroicon-m-document-currency-dollar')
                ->color('primary')
                ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),
        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
} 