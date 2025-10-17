<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RecentEmailActivityWidget;
use App\Filament\Widgets\RelatorioFinanceiroWidget;
use App\Filament\Widgets\ClienteAPIWidget;
use App\Filament\Widgets\SistemaOverviewWidget;
use App\Filament\Widgets\DashboardIndicadoresWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'PAINEL DE CONTROLE';
    protected static ?string $title = 'PAINEL DE CONTROLE';
    protected static ?int $navigationSort = -2;

    public function __construct()
    {
        $user = Auth::user();
        Log::info('Dashboard class instantiated by user: ' . ($user ? $user->id : 'not logged in'));
    }

    public function getColumns(): int|array
    {
        return 2;
    }

    public function getHeaderWidgets(): array
    {
        return [];
    }

    public function getVisibleWidgets(): array
    {
        $user = Auth::user();
        $userRoles = $user ? $user->roles->pluck('name')->toArray() : [];
        
        Log::info('Dashboard::getVisibleWidgets() - Determinando widgets visíveis', [
            'user_id' => $user ? $user->id : null,
            'user_email' => $user ? $user->email : null,
            'user_roles' => $userRoles,
            'timestamp' => now()->toDateTimeString()
        ]);
        
        $widgets = [];
        
        if ($user->hasRole('admin')) {
            $widgets = [
                DashboardIndicadoresWidget::class,
                StatsOverview::class,
                RecentEmailActivityWidget::class,
            ];
        } elseif ($user->hasRole('socio-gestor')) {
            $widgets = [
                DashboardIndicadoresWidget::class,
                SistemaOverviewWidget::class,
                StatsOverview::class,
                RelatorioFinanceiroWidget::class,
                RecentEmailActivityWidget::class,
                \App\Filament\Widgets\SessionDebugWidget::class,
            ];
        } elseif ($user->hasRole('proprietario')) {
            $widgets = [
                DashboardIndicadoresWidget::class,
                SistemaOverviewWidget::class,
                StatsOverview::class,
                RelatorioFinanceiroWidget::class,
                RecentEmailActivityWidget::class,
            ];
        } elseif ($user->hasRole('clienteapi')) {
            $widgets = [
                ClienteAPIWidget::class,
            ];
        } elseif ($user->hasRole('moderador')) {
            $widgets = [
                StatsOverview::class,
            ];
        } elseif ($user->hasRole('pesquisador')) {
            $widgets = [
                StatsOverview::class,
            ];
        } elseif ($user->hasRole('financeiro')) {
            $widgets = [
                StatsOverview::class,
            ];
        } else {
            $widgets = [
            StatsOverview::class,
        ];
        }
        
        Log::info('Dashboard::getVisibleWidgets() - Widgets determinados', [
            'user_id' => $user ? $user->id : null,
            'user_roles' => $userRoles,
            'widgets' => $widgets,
            'widgets_count' => count($widgets),
            'timestamp' => now()->toDateTimeString()
        ]);
        
        return $widgets;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(): void
    {
        $user = Auth::user();
        
        if (!$user) {
            Log::error('Dashboard mount: No authenticated user found');
            abort(403, 'Usuário não autenticado');
        }

        Log::info('Dashboard mounted successfully', [
            'user_id' => $user->id,
            'user_email' => $user->email
        ]);
    }
} 