<?php

namespace App\Filament\Resources\ApiRequisicaoResource\Pages;

use App\Filament\Resources\ApiRequisicaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApiRequisicao extends EditRecord
{
    protected static string $resource = ApiRequisicaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        redirect()->to($this->getResource()::getUrl('index'));
    }
}
