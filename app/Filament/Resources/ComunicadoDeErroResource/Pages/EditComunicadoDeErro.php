<?php

namespace App\Filament\Resources\ComunicadoDeErroResource\Pages;

use App\Filament\Resources\ComunicadoDeErroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComunicadoDeErro extends EditRecord
{
    protected static string $resource = ComunicadoDeErroResource::class;

    protected function afterSave(): void
    {
        $this->redirect(ComunicadoDeErroResource::getUrl('index'));
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
