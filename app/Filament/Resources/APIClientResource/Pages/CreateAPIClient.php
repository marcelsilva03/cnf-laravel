<?php

namespace App\Filament\Resources\APIClientResource\Pages;

use App\Filament\Resources\APIClientResource;
use App\Models\APIClient;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAPIClient extends CreateRecord
{
    protected static string $resource = APIClientResource::class;

    protected function afterSave(): void
    {
        $this->redirect(APIClientResource::getUrl('index'));
    }
    protected function handleRecordCreation(array $data): Model
    {
        $user = auth()->user();
        $apiClient = new APIClient();
        $apiClient->name = $data['name'];
        $apiClient->user_email = $user->email;
        $apiClient->save();
        return $apiClient;
    }
}
