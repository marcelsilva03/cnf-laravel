<?php

namespace App\Filament\Resources\APIClientResource\Pages;

use App\Filament\Resources\APIClientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAPIClient extends EditRecord
{
    protected static string $resource = APIClientResource::class;

    protected function afterSave(): void
    {
        $this->redirect(APIClientResource::getUrl('index'));
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return $apiClient;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
