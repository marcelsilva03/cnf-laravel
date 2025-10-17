<?php

namespace App\Filament\Resources\FaturamentoResource\Pages;

use App\Filament\Resources\FaturamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaturamento extends ListRecords
{
    protected static string $resource = FaturamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
