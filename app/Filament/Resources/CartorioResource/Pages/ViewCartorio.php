<?php

namespace App\Filament\Resources\CartorioResource\Pages;

use App\Filament\Resources\CartorioResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCartorio extends ViewRecord
{
    protected static string $resource = CartorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}