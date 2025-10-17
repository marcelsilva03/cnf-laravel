<?php

namespace App\Filament\Resources\FaturamentoResource\Pages;

use App\Filament\Resources\FaturamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaturamento extends EditRecord
{
    protected static string $resource = FaturamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function afterSave(): void
    {
        redirect()->to($this->getResource()::getUrl('index'));
    }
}
