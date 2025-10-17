<?php

namespace App\Filament\Resources\APIClientResource\Pages;

use App\Filament\Resources\APIClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAPIClients extends ListRecords
{
    protected static string $resource = APIClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
