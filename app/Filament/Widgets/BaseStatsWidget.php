<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

abstract class BaseStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getMonthRange(): array
    {
        $now = Carbon::now();
        return [
            $now->startOfMonth(),
            $now->copy()->endOfMonth()
        ];
    }

    protected function makeStat(string $name, string|int|float $value, string $description, string $icon, string $color = 'primary'): Stat
    {
        $bgColors = [
            'primary' => 'rgb(59 130 246 / 0.1)',
            'success' => 'rgb(34 197 94 / 0.1)',
            'danger' => 'rgb(239 68 68 / 0.1)',
            'warning' => 'rgb(245 158 11 / 0.1)',
            'info' => 'rgb(6 182 212 / 0.1)',
            'gray' => 'rgb(107 114 128 / 0.1)',
        ];

        return Stat::make($name, $value)
            ->description($description)
            ->descriptionIcon($icon)
            ->color($color)
            ->extraAttributes(['style' => 'background-color: ' . ($bgColors[$color] ?? $bgColors['primary'])]);
    }
} 