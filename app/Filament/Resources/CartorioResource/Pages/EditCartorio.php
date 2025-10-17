<?php

namespace App\Filament\Resources\CartorioResource\Pages;

use App\Filament\Resources\CartorioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCartorio extends EditRecord
{
    protected static string $resource = CartorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}