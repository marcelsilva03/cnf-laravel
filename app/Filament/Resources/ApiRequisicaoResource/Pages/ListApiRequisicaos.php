<?php

namespace App\Filament\Resources\ApiRequisicaoResource\Pages;

use App\Filament\Resources\ApiRequisicaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApiRequisicaos extends ListRecords
{
    protected static string $resource = ApiRequisicaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
