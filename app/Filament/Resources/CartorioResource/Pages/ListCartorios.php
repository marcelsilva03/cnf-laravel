<?php

namespace App\Filament\Resources\CartorioResource\Pages;

use App\Filament\Resources\CartorioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCartorios extends ListRecords
{
    protected static string $resource = CartorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}