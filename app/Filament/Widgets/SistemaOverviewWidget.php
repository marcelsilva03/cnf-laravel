<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\APIClient;
use App\Models\Solicitacao;
use App\Models\Faturamento;
use App\Models\Homenagem;
use App\Models\ComunicadoDeErro;
use App\Models\ComunicadoDeObito;
use App\Models\Falecido;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SistemaOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['proprietario', 'socio-gestor']);
    }

    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $startOfYear = $now->copy()->startOfYear();

        // Usuários do sistema
        $totalUsuarios = User::count();
        $usuariosAtivos = User::where('status', 1)->count();
        $usuariosInativos = User::where('status', 0)->count();
        $novosUsuariosEsteMes = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // Clientes API
        $clientesAPI = APIClient::count();
        $clientesAPIAtivos = APIClient::where('status', 1)->count();
        $clientesAPIInativos = APIClient::where('status', 0)->count();

        // Base de dados de falecidos
        $totalFalecidos = Falecido::where('fal_status', 1)->count();
        $falecidosAdicionadosEsteMes = Falecido::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // Atividade do sistema (este mês)
        $solicitacoesEsteMes = Solicitacao::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $homenagensEsteMes = Homenagem::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $comunicadosErroEsteMes = ComunicadoDeErro::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $comunicadosObitoEsteMes = ComunicadoDeObito::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // Pendências que precisam de atenção
        $homenagensModeracao = Homenagem::where('hom_status', 0)->count();
        $errosModeracao = ComunicadoDeErro::where('status', 0)->count();
        $obitosModeracao = ComunicadoDeObito::where('status', 0)->count();
        $solicitacoesPendentes = Solicitacao::where('sol_status', Solicitacao::STATUS['PENDENTE'])->count();

        // Receita e faturamento
        $receitaEsteMes = Faturamento::where('status', 'concluido')
            ->whereBetween('data_pagamento', [$startOfMonth, $endOfMonth])
            ->sum('valor');
        $receitaEsteAno = Faturamento::where('status', 'concluido')
            ->whereBetween('data_pagamento', [$startOfYear, $now])
            ->sum('valor');
        $faturamentoPendente = Faturamento::where('status', 'pendente')->sum('valor');

        return [
            // Usuários do Sistema
            Stat::make('Total de Usuários', $totalUsuarios)
                ->description("Ativos: {$usuariosAtivos} | Inativos: {$usuariosInativos}")
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),

            Stat::make('Novos Usuários (Mês)', $novosUsuariosEsteMes)
                ->description('Cadastros realizados este mês')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

            // Clientes API
            Stat::make('Clientes API', $clientesAPI)
                ->description("Ativos: {$clientesAPIAtivos} | Inativos: {$clientesAPIInativos}")
                ->descriptionIcon('heroicon-m-code-bracket')
                ->color('info')
                ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),

            // Base de Dados
            Stat::make('Base de Falecidos', number_format($totalFalecidos, 0, ',', '.'))
                ->description("Novos este mês: {$falecidosAdicionadosEsteMes}")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray')
                ->extraAttributes(['style' => 'background-color: rgb(107 114 128 / 0.1)']),

            // Atividade do Sistema
            Stat::make('Solicitações (Mês)', $solicitacoesEsteMes)
                ->description('Pedidos de certidões')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary')
                ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),

            Stat::make('Homenagens (Mês)', $homenagensEsteMes)
                ->description('Homenagens criadas')
                ->descriptionIcon('heroicon-m-heart')
                ->color('pink')
                ->extraAttributes(['style' => 'background-color: rgb(236 72 153 / 0.1)']),

            // Pendências (Alertas)
            Stat::make('Pendências de Moderação', $homenagensModeracao + $errosModeracao + $obitosModeracao)
                ->description("Homenagens: {$homenagensModeracao} | Erros: {$errosModeracao} | Óbitos: {$obitosModeracao}")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($homenagensModeracao + $errosModeracao + $obitosModeracao > 0 ? 'warning' : 'success')
                ->extraAttributes(['style' => $homenagensModeracao + $errosModeracao + $obitosModeracao > 0 ? 
                    'background-color: rgb(245 158 11 / 0.1)' : 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Solicitações Pendentes', $solicitacoesPendentes)
                ->description('Aguardando aprovação')
                ->descriptionIcon('heroicon-m-clock')
                ->color($solicitacoesPendentes > 0 ? 'warning' : 'success')
                ->extraAttributes(['style' => $solicitacoesPendentes > 0 ? 
                    'background-color: rgb(245 158 11 / 0.1)' : 'background-color: rgb(34 197 94 / 0.1)']),

            // Financeiro
            Stat::make('Receita Este Mês', 'R$ ' . number_format($receitaEsteMes, 2, ',', '.'))
                ->description('Pagamentos recebidos')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Receita Este Ano', 'R$ ' . number_format($receitaEsteAno, 2, ',', '.'))
                ->description('Total anual')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Faturamento Pendente', 'R$ ' . number_format($faturamentoPendente, 2, ',', '.'))
                ->description('Aguardando pagamento')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color($faturamentoPendente > 0 ? 'warning' : 'success')
                ->extraAttributes(['style' => $faturamentoPendente > 0 ? 
                    'background-color: rgb(245 158 11 / 0.1)' : 'background-color: rgb(34 197 94 / 0.1)']),

            // Comunicados
            Stat::make('Comunicados de Erro (Mês)', $comunicadosErroEsteMes)
                ->description('Relatórios de problemas')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger')
                ->extraAttributes(['style' => 'background-color: rgb(239 68 68 / 0.1)']),

            Stat::make('Comunicados de Óbito (Mês)', $comunicadosObitoEsteMes)
                ->description('Novos óbitos reportados')
                ->descriptionIcon('heroicon-m-document-plus')
                ->color('info')
                ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),
        ];
    }
} 