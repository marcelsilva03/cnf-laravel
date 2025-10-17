<?php

namespace App\Filament\Resources\RelatorioFaturamentoResource\Pages;

use App\Filament\Resources\RelatorioFaturamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRelatorioFaturamentos extends ListRecords
{
    protected static string $resource = RelatorioFaturamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
