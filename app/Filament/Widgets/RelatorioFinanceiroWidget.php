<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Faturamento;
use App\Models\Solicitacao;
use App\Models\User;
use Carbon\Carbon;

class RelatorioFinanceiroWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    
    // Remove método getName() - deixa o Livewire gerar automaticamente
    


    public static function canView(): bool
    {
        $user = auth()->user();
        if (!$user) {
            \Log::warning('RelatorioFinanceiroWidget::canView() - Usuário não autenticado');
            return false;
        }
        
        // Lista explícita de roles permitidas
        $allowedRoles = ['admin', 'socio-gestor', 'proprietario', 'financeiro'];
        $userRoles = $user->roles->pluck('name')->toArray();
        $hasAllowedRole = !empty(array_intersect($userRoles, $allowedRoles));
        
        // Bloquear explicitamente moderadores
        $isModerador = $user->hasRole('moderador');
        $canView = $hasAllowedRole && !$isModerador;
        
        // Log detalhado para debug
        \Log::info('RelatorioFinanceiroWidget::canView()', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_roles' => $userRoles,
            'allowed_roles' => $allowedRoles,
            'has_allowed_role' => $hasAllowedRole,
            'is_moderador' => $isModerador,
            'can_view' => $canView,
            'timestamp' => now()->toDateTimeString()
        ]);
        
        return $canView;
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        
        // Proteção tripla: se for moderador, retorna array vazio IMEDIATAMENTE
        if (!$user || $user->hasRole('moderador')) {
            \Log::warning('RelatorioFinanceiroWidget::getStats() BLOQUEADO para moderador', [
                'user_id' => $user ? $user->id : null,
                'user_email' => $user ? $user->email : null,
                'is_moderador' => $user ? $user->hasRole('moderador') : false,
                'user_roles' => $user ? $user->roles->pluck('name')->toArray() : [],
                'timestamp' => now()->toDateTimeString(),
                'action' => 'BLOCKED_FINANCIAL_DATA_ACCESS'
            ]);
            return [];
        }
        
        // Verificação adicional: só permite roles específicos
        if (!$user->hasRole(['admin', 'socio-gestor', 'proprietario', 'financeiro'])) {
            \Log::warning('RelatorioFinanceiroWidget::getStats() BLOQUEADO - role não autorizada', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_roles' => $user->roles->pluck('name')->toArray(),
                'timestamp' => now()->toDateTimeString(),
                'action' => 'BLOCKED_UNAUTHORIZED_ROLE'
            ]);
            return [];
        }
        
        // Log de acesso autorizado
        \Log::info('RelatorioFinanceiroWidget::getStats() AUTORIZADO', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_roles' => $user->roles->pluck('name')->toArray(),
            'timestamp' => now()->toDateTimeString(),
            'action' => 'AUTHORIZED_FINANCIAL_DATA_ACCESS'
        ]);
        
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $startOfYear = $now->copy()->startOfYear();
        
        // Faturamentos por período
        $faturamentosHoje = Faturamento::whereDate('created_at', $now->toDateString())->count();
        $faturamentosEsteMes = Faturamento::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $faturamentosEsteAno = Faturamento::whereBetween('created_at', [$startOfYear, $now])->count();
        
        // Valores por status
        $valorPendente = Faturamento::where('status', 'pendente')->sum('valor');
        $valorConcluido = Faturamento::where('status', 'concluido')->sum('valor');
        $valorCanceladoEsteMes = Faturamento::where('status', 'cancelado')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('valor');
        
        // Receita por período
        $receitaHoje = Faturamento::where('status', 'concluido')
            ->whereDate('data_pagamento', $now->toDateString())
            ->sum('valor');
        $receitaEsteMes = Faturamento::where('status', 'concluido')
            ->whereBetween('data_pagamento', [$startOfMonth, $endOfMonth])
            ->sum('valor');
        $receitaEsteAno = Faturamento::where('status', 'concluido')
            ->whereBetween('data_pagamento', [$startOfYear, $now])
            ->sum('valor');
        
        // Métodos de pagamento mais usados
        $pagamentosPix = Faturamento::where('metodo', 'pix')->where('status', 'concluido')->count();
        $pagamentosCartao = Faturamento::where('metodo', 'cartao')->where('status', 'concluido')->count();
        $pagamentosBoleto = Faturamento::where('metodo', 'boleto')->where('status', 'concluido')->count();
        
        // Clientes ativos
        $clientesComFaturamento = Faturamento::distinct('user_id')->count('user_id');
        $clientesComPagamentosEsteMes = Faturamento::where('status', 'concluido')
            ->whereBetween('data_pagamento', [$startOfMonth, $endOfMonth])
            ->distinct('user_id')
            ->count('user_id');
        
        // Solicitações pendentes de aprovação
        $solicitacoesPendentes = Solicitacao::where('status', Solicitacao::STATUS['PENDENTE'])->count();
        
        return [
            // Faturamentos por período
            Stat::make('Faturamentos Hoje', $faturamentosHoje)
                ->description('Novos faturamentos criados hoje')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('info')
                ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),
                
            Stat::make('Faturamentos Este Mês', $faturamentosEsteMes)
                ->description('Total de faturamentos criados')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),
                
            Stat::make('Faturamentos Este Ano', $faturamentosEsteAno)
                ->description('Total anual de faturamentos')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('gray')
                ->extraAttributes(['style' => 'background-color: rgb(107 114 128 / 0.1)']),
            
            // Valores por status
            Stat::make('Valor Pendente', 'R$ ' . number_format($valorPendente, 2, ',', '.'))
                ->description('Aguardando pagamento')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),
                
            Stat::make('Valor Concluído', 'R$ ' . number_format($valorConcluido, 2, ',', '.'))
                ->description('Total de pagamentos processados')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),
                
            Stat::make('Valor Cancelado (Mês)', 'R$ ' . number_format($valorCanceladoEsteMes, 2, ',', '.'))
                ->description('Faturamentos cancelados este mês')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->extraAttributes(['style' => 'background-color: rgb(239 68 68 / 0.1)']),
            
            // Receita por período
            Stat::make('Receita Hoje', 'R$ ' . number_format($receitaHoje, 2, ',', '.'))
                ->description('Pagamentos recebidos hoje')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),
                
            Stat::make('Receita Este Mês', 'R$ ' . number_format($receitaEsteMes, 2, ',', '.'))
                ->description('Total de receita mensal')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),
                
            Stat::make('Receita Este Ano', 'R$ ' . number_format($receitaEsteAno, 2, ',', '.'))
                ->description('Total de receita anual')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),
            
            // Métodos de pagamento
            Stat::make('Pagamentos PIX', $pagamentosPix)
                ->description('Transações PIX concluídas')
                ->descriptionIcon('heroicon-m-qr-code')
                ->color('primary')
                ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),
                
            Stat::make('Pagamentos Cartão', $pagamentosCartao)
                ->description('Transações cartão concluídas')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('info')
                ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),
                
            Stat::make('Pagamentos Boleto', $pagamentosBoleto)
                ->description('Transações boleto concluídas')
                ->descriptionIcon('heroicon-m-document')
                ->color('warning')
                ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),
            
            // Clientes
            Stat::make('Clientes com Faturamento', $clientesComFaturamento)
                ->description('Total de clientes únicos')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray')
                ->extraAttributes(['style' => 'background-color: rgb(107 114 128 / 0.1)']),
                
            Stat::make('Clientes Ativos (Mês)', $clientesComPagamentosEsteMes)
                ->description('Clientes que pagaram este mês')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('primary')
                ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),
                
            Stat::make('Solicitações Pendentes', $solicitacoesPendentes)
                ->description('Aguardando aprovação financeira')
                ->descriptionIcon('heroicon-m-document-magnifying-glass')
                ->color('warning')
                ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),
        ];
    }
} 