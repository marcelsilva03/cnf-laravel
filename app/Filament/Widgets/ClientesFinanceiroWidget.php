<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Faturamento;
use App\Models\APIClient;
use Carbon\Carbon;

class ClientesFinanceiroWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole(['admin', 'financeiro', 'socio-gestor', 'proprietario']);
    }

    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Clientes por tipo
        $clientesAPI = User::whereHas('roles', function ($query) {
            $query->where('name', 'clienteapi');
        })->where('status', User::STATUS['ATIVO'])->count();

        $solicitantes = User::whereHas('roles', function ($query) {
            $query->where('name', 'solicitante');
        })->where('status', User::STATUS['ATIVO'])->count();

        $pesquisadores = User::whereHas('roles', function ($query) {
            $query->where('name', 'pesquisador');
        })->where('status', User::STATUS['ATIVO'])->count();

        // Clientes com faturamentos
        $clientesComFaturamento = Faturamento::distinct('user_id')->count('user_id');
        
        $clientesComPagamentosEsteMes = Faturamento::where('status', 'concluido')
            ->whereBetween('data_pagamento', [$startOfMonth, $endOfMonth])
            ->distinct('user_id')
            ->count('user_id');

        // Novos clientes este mês
        $novosClientesEsteMes = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['clienteapi', 'solicitante', 'pesquisador']);
        })
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();

        // Clientes API ativos
        $clientesAPIAtivos = APIClient::where('status', APIClient::STATUS['ATIVO'])->count();

        // Ticket médio
        $ticketMedio = Faturamento::where('status', 'concluido')
            ->whereBetween('data_pagamento', [$startOfMonth, $endOfMonth])
            ->avg('valor') ?? 0;

        return [
            Stat::make('Clientes API', $clientesAPI)
                ->description('Clientes ativos da API')
                ->descriptionIcon('heroicon-m-code-bracket')
                ->color('primary')
                ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),

            Stat::make('Solicitantes', $solicitantes)
                ->description('Usuários solicitantes ativos')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Pesquisadores', $pesquisadores)
                ->description('Usuários pesquisadores ativos')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('info')
                ->extraAttributes(['style' => 'background-color: rgb(6 182 212 / 0.1)']),

            Stat::make('Com Faturamentos', $clientesComFaturamento)
                ->description('Clientes que já faturaram')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning')
                ->extraAttributes(['style' => 'background-color: rgb(245 158 11 / 0.1)']),

            Stat::make('Pagaram Este Mês', $clientesComPagamentosEsteMes)
                ->description('Clientes com pagamentos processados')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),

            Stat::make('Novos Este Mês', $novosClientesEsteMes)
                ->description('Novos clientes cadastrados')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('primary')
                ->extraAttributes(['style' => 'background-color: rgb(59 130 246 / 0.1)']),

            Stat::make('APIs Ativas', $clientesAPIAtivos)
                ->description('Configurações de API ativas')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('gray')
                ->extraAttributes(['style' => 'background-color: rgb(107 114 128 / 0.1)']),

            Stat::make('Ticket Médio', 'R$ ' . number_format($ticketMedio, 2, ',', '.'))
                ->description('Valor médio dos pagamentos este mês')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->extraAttributes(['style' => 'background-color: rgb(34 197 94 / 0.1)']),
        ];
    }
} 