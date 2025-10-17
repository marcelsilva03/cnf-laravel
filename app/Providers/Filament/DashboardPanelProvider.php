<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\StatsOverview;
use App\Http\Middleware\ClearSessionOnProfileChange;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dashboard')
            ->path('admin')
            ->login()
            ->collapsibleNavigationGroups(true)
            ->sidebarCollapsibleOnDesktop()
            ->assets([
                Css::make('cnf-dashboard','css/filament/filament/cnf-dashboard.css'),
                Js::make('cnf-dashboard','js/filament/filament/cnf-dashboard.js'),
            ], 'filament/filament')
            ->colors([
                'primary' => 'rgb(0, 118, 44)',
                'secondary' => Color::Gray,
                'success' => 'rgb(0, 118, 44)',
            ])
            ->darkMode(false)
            ->favicon(asset('images/favicon.png'))
            ->brandLogo(fn () => view(
                'partials.logo', [
                    'url' => route('home'),
                    'width' => '65',
                ]
            ))
            ->brandLogoHeight('50px')
            ->brandName('CNF')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->widgets([
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\RecentEmailActivityWidget::class,
                \App\Filament\Widgets\ClienteAPIWidget::class,
                \App\Filament\Widgets\SistemaOverviewWidget::class,
                \App\Filament\Widgets\SessionDebugWidget::class,
                \App\Filament\Widgets\DashboardIndicadoresWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                ClearSessionOnProfileChange::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
