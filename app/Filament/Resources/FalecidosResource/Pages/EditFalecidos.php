<?php

namespace App\Filament\Resources\FalecidosResource\Pages;

use App\Filament\Resources\FalecidosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFalecidos extends EditRecord
{
    protected static string $resource = FalecidosResource::class;

    protected function afterSave(): void
    {
        $this->redirect(FalecidosResource::getUrl('index'));
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
