<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\APIClient;
use App\Models\ApiRequisicao;
use App\Models\Faturamento;
use Illuminate\Support\Facades\Auth;

class ClienteAPIWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        
        // Buscar dados do cliente API
        $apiClient = APIClient::where('user_email', $user->email)->first();
        
        if (!$apiClient) {
            return [
                Stat::make('Status da API', 'Não configurado')
                    ->description('Configure sua chave de API')
                    ->color('danger'),
            ];
        }

        // Calcular estatísticas
        $limiteMensal = $apiClient->request_limit ?? 0;
        $usoAtual = ApiRequisicao::where('api_key', $apiClient->api_key)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $percentualUso = $limiteMensal > 0 ? round(($usoAtual / $limiteMensal) * 100, 1) : 0;
        $restante = max(0, $limiteMensal - $usoAtual);
        
        // Faturamentos do cliente
        $faturamentoPendente = Faturamento::where('user_id', $user->id)
            ->where('status', 'pendente')
            ->sum('valor');
            
        $faturamentoMes = Faturamento::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('valor');

        return [
            Stat::make('Limite Mensal', number_format($limiteMensal, 0, ',', '.'))
                ->description('Requisições permitidas por mês')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Uso Atual', number_format($usoAtual, 0, ',', '.'))
                ->description("{$percentualUso}% do limite utilizado")
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($percentualUso > 80 ? 'danger' : ($percentualUso > 60 ? 'warning' : 'success')),

            Stat::make('Disponível', number_format($restante, 0, ',', '.'))
                ->description('Requisições restantes')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),

            Stat::make('Pagamento Pendente', 'R$ ' . number_format($faturamentoPendente, 2, ',', '.'))
                ->description('Valor em aberto')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($faturamentoPendente > 0 ? 'warning' : 'success'),

            Stat::make('Faturamento do Mês', 'R$ ' . number_format($faturamentoMes, 2, ',', '.'))
                ->description('Total faturado este mês')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),

            Stat::make('Status da API', $apiClient->status == 1 ? 'Ativa' : 'Inativa')
                ->description('Estado atual da sua API')
                ->descriptionIcon('heroicon-m-signal')
                ->color($apiClient->status == 1 ? 'success' : 'danger'),
        ];
    }

    protected static ?int $sort = 1;
    
    public static function canView(): bool
    {
        return auth()->user()->hasRole('clienteapi');
    }
} 