<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Solicitacao;
use App\Models\Homenagem;
use App\Models\ComunicadoDeErro;
use App\Models\ComunicadoDeObito;
use App\Models\Contrato;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Falecido;
use App\Models\Faturamento;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $user = auth()->user();
        
        if (!$user) {
            \Log::error('StatsOverview::getStats() - Usuário não autenticado');
            return [];
        }
        
        $userRoles = $user->roles->pluck('name')->toArray();
        
        // Log para diagnóstico do problema
        \Log::info('StatsOverview::getStats - Verificando roles do usuário', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'roles' => $userRoles,
            'is_solicitante' => $user->hasRole('solicitante'),
            'is_admin' => $user->hasRole('admin'),
            'is_proprietario' => $user->hasRole('proprietario'),
            'is_socio_gestor' => $user->hasRole('socio-gestor'),
            'is_pesquisador' => $user->hasRole('pesquisador'),
        ]);
        
        // CORREÇÃO: Verificar roles específicos primeiro, depois roles gerais
        // Ordem corrigida para evitar conflitos de múltiplos roles
        
        // 1. ROLES ESPECÍFICOS PRIMEIRO (para evitar conflitos)
        // CORREÇÃO CRÍTICA: Verificação individual de roles para compatibilidade total com Spatie
        if ($user->hasRole('solicitante') && !($user->hasRole('admin') || $user->hasRole('proprietario') || $user->hasRole('socio-gestor'))) {
            // Buscar solicitações do usuário logado usando email
            $minhasSolicitacoes = Solicitacao::where('sol_email_sol', $user->email)->count();
            $solicitacoesPendentes = Solicitacao::where('sol_email_sol', $user->email)
                ->where('status', Solicitacao::STATUS['PENDENTE'])->count();
            $solicitacoesPagas = Solicitacao::where('sol_email_sol', $user->email)
                ->where('status', Solicitacao::STATUS['PAGA'])->count();
            $solicitacoesLiberadas = Solicitacao::where('sol_email_sol', $user->email)
                ->where('status', Solicitacao::STATUS['LIBERADA'])->count();
            
            return [
                Stat::make('Minhas Solicitações', $minhasSolicitacoes)
                    ->description('Clique para ver todas as suas solicitações')
                    ->descriptionIcon('heroicon-m-document')
                    ->color('primary')
                    ->url(\App\Filament\Resources\SolicitacaoResource::getUrl('index'))
                    ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),
                    
                Stat::make('Solicitações Pendentes', $solicitacoesPendentes)
                    ->description('Clique para ver detalhes das pendentes')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning')
                    ->url(\App\Filament\Resources\SolicitacaoResource::getUrl('index'))
                    ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),
                    
                Stat::make('Solicitações Pagas', $solicitacoesPagas)
                    ->description('Clique para ver detalhes das pagas')
                    ->descriptionIcon('heroicon-m-banknotes')
                    ->color('success')
                    ->url(\App\Filament\Resources\SolicitacaoResource::getUrl('index'))
                    ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),
                    
                Stat::make('Solicitações Liberadas', $solicitacoesLiberadas)
                    ->description('Clique para ver detalhes das liberadas')
                    ->descriptionIcon('heroicon-m-check-badge')
                    ->color('info')
                    ->url(\App\Filament\Resources\SolicitacaoResource::getUrl('index'))
                    ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),
            ];
        }

        switch (true) {
            case $user->hasRole('clienteapi'):
                // Get the first contract for testing (we'll update this logic later)
                $contrato = Contrato::first();
                
                if (!$contrato) {
                    // Return empty stats with a message if no contract exists
                    return [
                        Stat::make('Status', 'Chave não encontrada')
                            ->description('Crie uma chave no menu API -> Client Key.')
                            ->descriptionIcon('heroicon-m-exclamation-triangle')
                            ->color('danger')
                            ->extraAttributes(['style' => 'background-color: rgb(239 68 68 / 0.1)']),
                    ];
                }

                // Calculate next payment date (assuming monthly payments)
                $nextPayment = Carbon::parse($contrato->created_at)->addMonth();
                $lastPayment = Carbon::parse($contrato->created_at);
                
                // Calculate remaining requests
                $remainingRequests = $contrato->request_limit - $contrato->requests_made;
                
                return [
                    Stat::make('Chaves API vigentes', $contrato->api_key ? 1 : 0)
                        ->description('Ativas')
                        ->descriptionIcon('heroicon-m-key')
                        ->color('success')
                        ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

                    Stat::make('Último pagamento', $lastPayment->format('d/m/Y'))
                        ->description('Data')
                        ->descriptionIcon('heroicon-m-banknotes')
                        ->color('info')
                        ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),

                    Stat::make('Próximo pagamento', $nextPayment->format('d/m/Y'))
                        ->description('Data')
                        ->descriptionIcon('heroicon-m-calendar')
                        ->color('warning')
                        ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),

                    Stat::make('Total de Requisições', $contrato->requests_made)
                        ->description('Realizadas')
                        ->descriptionIcon('heroicon-m-arrow-path')
                        ->color('primary')
                        ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),

                    Stat::make('Limite de Requisições', $contrato->request_limit)
                        ->description('Total disponível')
                        ->descriptionIcon('heroicon-m-chart-bar')
                        ->color('gray')
                        ->extraAttributes(['style' => 'background-color: rgb(107 114 128 / 0.1)']),

                    Stat::make('Requisições restantes', $remainingRequests)
                        ->description('Disponíveis')
                        ->descriptionIcon('heroicon-m-check-circle')
                        ->color($remainingRequests < 100 ? 'danger' : 'success')
                        ->extraAttributes(['style' => $remainingRequests < 100 ? 
                            'background-color: rgb(239 68 68 / 0.1)' : 
                            'background-color: rgb(34 197 94 / 0.1)']),
                ];
                
            case $user->hasRole('proprietario'):
            case $user->hasRole('socio-gestor'):
                $now = Carbon::now();
                $startOfMonth = $now->startOfMonth();
                $endOfMonth = $now->copy()->endOfMonth();

                // Get monthly statistics
                $totalSolicitacoes = Solicitacao::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $solicitacoesPagas = Solicitacao::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('status', Solicitacao::STATUS['PAGA'])
                    ->count();
                $solicitacoesCanceladas = Solicitacao::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('status', Solicitacao::STATUS['REJEITADA'])
                    ->count();

                // Payment statistics
                $pagamentosPix = Solicitacao::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('pag_metodo_escolhido', 'pix')
                    ->where('status', Solicitacao::STATUS['PAGA'])
                    ->sum('sol_valor');
                $pagamentosCartao = Solicitacao::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('pag_metodo_escolhido', 'cartao')
                    ->where('status', Solicitacao::STATUS['PAGA'])
                    ->sum('sol_valor');
                $pagamentosBoleto = Solicitacao::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('pag_metodo_escolhido', 'boleto')
                    ->where('status', Solicitacao::STATUS['PAGA'])
                    ->sum('sol_valor');

                // Homenagens statistics
                $totalHomenagens = Homenagem::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $homenagensModeracao = Homenagem::where('hom_status', 0)->count();

                // Comunicados de Erros
                $errosModeracao = ComunicadoDeErro::where('status', 0)->count();
                $novosErros = ComunicadoDeErro::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

                // Óbitos statistics
                $obitosModeracao = ComunicadoDeObito::where('status', 0)->count();
                $novosObitos = ComunicadoDeObito::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

                // Contratos statistics
                $contratosVigentes = Contrato::count();
                $contratosVencendo = Contrato::where('request_limit', '<=', 100)->count();
                $contratosAPI = Contrato::count();
                $contratosObitos = Contrato::where('requests_made', '>', 0)->count();

                return [
                    // Solicitações
                    Stat::make('Total de Solicitações', $totalSolicitacoes)
                        ->description('Este mês')
                        ->descriptionIcon('heroicon-m-shopping-cart')
                        ->color('primary')
                        ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),
                    
                    Stat::make('Solicitações Pagas', $solicitacoesPagas)
                        ->description('Este mês')
                        ->descriptionIcon('heroicon-m-banknotes')
                        ->color('success')
                        ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

                    Stat::make('Solicitações Canceladas', $solicitacoesCanceladas)
                        ->description('Emitidas sem pagamento')
                        ->descriptionIcon('heroicon-m-x-circle')
                        ->color('danger')
                        ->extraAttributes(['style' => 'background-color: rgb(239 68 68 / 0.1)']),

                    // Pagamentos
                    Stat::make('Pagamentos via PIX', 'R$ ' . number_format($pagamentosPix, 2, ',', '.'))
                        ->description('Este mês')
                        ->descriptionIcon('heroicon-m-qr-code')
                        ->color('info')
                        ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),

                    Stat::make('Pagamentos via Cartão', 'R$ ' . number_format($pagamentosCartao, 2, ',', '.'))
                        ->description('Este mês')
                        ->descriptionIcon('heroicon-m-credit-card')
                        ->color('info')
                        ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),

                    Stat::make('Pagamentos via Boleto', 'R$ ' . number_format($pagamentosBoleto, 2, ',', '.'))
                        ->description('Este mês')
                        ->descriptionIcon('heroicon-m-document-text')
                        ->color('info')
                        ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),

                    // Homenagens
                    Stat::make('Total de Homenagens', $totalHomenagens)
                        ->description('Este mês')
                        ->descriptionIcon('heroicon-m-heart')
                        ->color('gray')
                        ->extraAttributes(['style' => 'background-color: rgb(107 114 128 / 0.1)']),

                    Stat::make('Homenagens em Moderação', $homenagensModeracao)
                        ->description('Aguardando aprovação')
                        ->descriptionIcon('heroicon-m-clock')
                        ->color('warning')
                        ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),

                    // Comunicados e Óbitos
                    Stat::make('Erros Aguardando Moderação', $errosModeracao)
                        ->description('Precisa de atenção')
                        ->descriptionIcon('heroicon-m-exclamation-triangle')
                        ->color('danger')
                        ->extraAttributes(['style' => 'background-color: rgb(239 68 68 / 0.1)']),

                    Stat::make('Óbitos Aguardando Moderação', $obitosModeracao)
                        ->description('Precisa de atenção')
                        ->descriptionIcon('heroicon-m-clock')
                        ->color('warning')
                        ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),

                    Stat::make('Novos Óbitos', $novosObitos)
                        ->description('Este mês')
                        ->descriptionIcon('heroicon-m-document-plus')
                        ->color('primary')
                        ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),

                    Stat::make('Novos Comunicados de Erros', $novosErros)
                        ->description('Este mês')
                        ->descriptionIcon('heroicon-m-exclamation-circle')
                        ->color('danger')
                        ->extraAttributes(['style' => 'background-color: rgb(239 68 68 / 0.1)']),

                    // Contratos
                    Stat::make('Contratos Vigentes', $contratosVigentes)
                        ->description('Ativos')
                        ->descriptionIcon('heroicon-m-document-check')
                        ->color('success')
                        ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

                    Stat::make('Contratos Vencendo', $contratosVencendo)
                        ->description('Próximos do limite de requisições')
                        ->descriptionIcon('heroicon-m-clock')
                        ->color('warning')
                        ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),

                    Stat::make('Contratos API', $contratosAPI)
                        ->description('Total de contratos')
                        ->descriptionIcon('heroicon-m-code-bracket')
                        ->color('gray')
                        ->extraAttributes(['style' => 'background-color: rgb(107 114 128 / 0.1)']),

                    Stat::make('Contratos com Uso', $contratosObitos)
                        ->description('Com requisições realizadas')
                        ->descriptionIcon('heroicon-m-document')
                        ->color('primary')
                        ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),
                ];
                // FIM DO CASE SOCIO-GESTOR - IMPORTANTE: Este return garante que não há fall-through
                
            case $user->hasRole('admin'):
                return [
                    Stat::make('Total de Usuários', User::count())
                        ->description('Total de usuários no sistema')
                        ->descriptionIcon('heroicon-m-users')
                        ->color('primary')
                        ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),

                    Stat::make('Usuários Ativos', User::where('status', User::STATUS['ATIVO'])->count())
                        ->description('Usuários ativos')
                        ->descriptionIcon('heroicon-m-user-circle')
                        ->color('success')
                        ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

                    Stat::make('Usuários Inativos', User::where('status', User::STATUS['INATIVO'])->count())
                        ->description('Usuários inativos')
                        ->descriptionIcon('heroicon-m-user-circle')
                        ->color('danger')
                        ->extraAttributes(['style' => 'background-color: rgb(239 68 68 / 0.1)']),
                ];

            case $user->hasRole('moderador'):
                return [
                    Stat::make('Homenagens em Moderação', Homenagem::where('hom_status', 0)->count())
                        ->description('Aguardando aprovação')
                        ->descriptionIcon('heroicon-m-heart')
                        ->color('warning')
                        ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),

                    Stat::make('Comunicados de Erro', ComunicadoDeErro::where('status', ComunicadoDeErro::STATUS['PENDENTE'])->count())
                        ->description('Aguardando moderação')
                        ->descriptionIcon('heroicon-m-exclamation-triangle')
                        ->color('danger')
                        ->extraAttributes(['style' => 'background-color: rgb(239 68 68 / 0.1)']),
                        
                    Stat::make('Comunicados de Óbito', ComunicadoDeObito::where('status', ComunicadoDeObito::STATUS['PENDENTE'])->count())
                        ->description('Aguardando moderação')
                        ->descriptionIcon('heroicon-m-clock')
                        ->color('warning')
                        ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),
                ];

            case $user->hasRole('pesquisador'):
                return [
                    Stat::make('Total de Falecidos', Falecido::where('fal_status', 1)->count())
                        ->description('Registros ativos no sistema')
                        ->descriptionIcon('heroicon-m-users')
                        ->color('primary')
                        ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),
                        
                    Stat::make('Comunicados de Erro', ComunicadoDeErro::where('email_comunicante', $user->email)->count())
                        ->description('Seus comunicados enviados')
                        ->descriptionIcon('heroicon-m-exclamation-triangle')
                        ->color('warning')
                        ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),
                        
                    Stat::make('Comunicados Pendentes', ComunicadoDeErro::where('email_comunicante', $user->email)
                        ->where('status', ComunicadoDeErro::STATUS['PENDENTE'])->count())
                        ->description('Aguardando análise')
                        ->descriptionIcon('heroicon-m-clock')
                        ->color('info')
                        ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),
                ];

            case $user->hasRole('financeiro'):
                $now = Carbon::now();
                $startOfMonth = $now->copy()->startOfMonth();
                $endOfMonth = $now->copy()->endOfMonth();
                
                // Faturamentos
                $totalFaturamentos = Faturamento::count();
                $faturamentosEsteMes = Faturamento::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $faturamentosPendentes = Faturamento::where('status', 'pendente')->count();
                $faturamentosConcluidos = Faturamento::where('status', 'concluido')->count();
                
                // Valores
                $valorTotalFaturado = Faturamento::where('status', 'concluido')->sum('valor');
                $valorEsteMes = Faturamento::where('status', 'concluido')
                    ->whereBetween('data_pagamento', [$startOfMonth, $endOfMonth])
                    ->sum('valor');
                $valorPendente = Faturamento::where('status', 'pendente')->sum('valor');
                
                // Solicitações
                $solicitacoesPendentes = Solicitacao::where('status', Solicitacao::STATUS['PENDENTE'])->count();
                $solicitacoesAprovadas = Solicitacao::where('status', Solicitacao::STATUS['PAGA'])->count();
                
                // Métodos de pagamento mais usados
                $pagamentosPix = Faturamento::where('metodo', 'pix')->where('status', 'concluido')->count();
                $pagamentosCartao = Faturamento::where('metodo', 'cartao')->where('status', 'concluido')->count();
                
                return [
                    Stat::make('Total de Faturamentos', $totalFaturamentos)
                        ->description('Total de faturamentos no sistema')
                        ->descriptionIcon('heroicon-m-document-text')
                        ->color('primary')
                        ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),
                        
                    Stat::make('Faturamentos Este Mês', $faturamentosEsteMes)
                        ->description('Novos faturamentos criados')
                        ->descriptionIcon('heroicon-m-plus-circle')
                        ->color('info')
                        ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),
                        
                    Stat::make('Faturamentos Pendentes', $faturamentosPendentes)
                        ->description('Aguardando processamento')
                        ->descriptionIcon('heroicon-m-clock')
                        ->color('warning')
                        ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),
                        
                    Stat::make('Faturamentos Concluídos', $faturamentosConcluidos)
                        ->description('Pagamentos processados')
                        ->descriptionIcon('heroicon-m-check-circle')
                        ->color('success')
                        ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),
                        
                    Stat::make('Valor Total Faturado', 'R$ ' . number_format($valorTotalFaturado, 2, ',', '.'))
                        ->description('Soma de todos os pagamentos')
                        ->descriptionIcon('heroicon-m-banknotes')
                        ->color('success')
                        ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),
                        
                    Stat::make('Receita Este Mês', 'R$ ' . number_format($valorEsteMes, 2, ',', '.'))
                        ->description('Pagamentos recebidos este mês')
                        ->descriptionIcon('heroicon-m-currency-dollar')
                        ->color('info')
                        ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),
                        
                    Stat::make('Valor Pendente', 'R$ ' . number_format($valorPendente, 2, ',', '.'))
                        ->description('Aguardando pagamento')
                        ->descriptionIcon('heroicon-m-exclamation-triangle')
                        ->color('warning')
                        ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),
                        
                    Stat::make('Solicitações Pendentes', $solicitacoesPendentes)
                        ->description('Aguardando aprovação')
                        ->descriptionIcon('heroicon-m-document-magnifying-glass')
                        ->color('warning')
                        ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),
                        
                    Stat::make('Solicitações Aprovadas', $solicitacoesAprovadas)
                        ->description('Total de aprovações')
                        ->descriptionIcon('heroicon-m-check-badge')
                        ->color('success')
                        ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),
                        
                    Stat::make('Pagamentos via PIX', $pagamentosPix)
                        ->description('Transações PIX concluídas')
                        ->descriptionIcon('heroicon-m-qr-code')
                        ->color('primary')
                        ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),
                        
                    Stat::make('Pagamentos via Cartão', $pagamentosCartao)
                        ->description('Transações cartão concluídas')
                        ->descriptionIcon('heroicon-m-credit-card')
                        ->color('info')
                        ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),
                ];


            default:
                return [
                    Stat::make('Total de Usuários', User::count())
                        ->description('Usuários registrados no sistema')
                        ->descriptionIcon('heroicon-m-users')
                        ->color('success'),
                    
                    Stat::make('Usuários Ativos', User::where('status', User::STATUS['ATIVO'])->count())
                        ->description('Usuários com status ativo')
                        ->descriptionIcon('heroicon-m-user-check')
                        ->color('success'),
                    
                    Stat::make('Usuários Inativos', User::where('status', User::STATUS['INATIVO'])->count())
                        ->description('Usuários com status inativo')
                        ->descriptionIcon('heroicon-m-user-x-mark')
                        ->color('danger'),
                ];
        }
    }
}