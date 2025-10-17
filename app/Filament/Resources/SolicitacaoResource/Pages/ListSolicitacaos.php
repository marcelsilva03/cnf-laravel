<?php

namespace App\Filament\Resources\SolicitacaoResource\Pages;

use App\Filament\Resources\SolicitacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSolicitacaos extends ListRecords
{
    protected static string $resource = SolicitacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
