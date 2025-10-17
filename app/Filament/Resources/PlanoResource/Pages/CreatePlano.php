<?php

namespace App\Filament\Resources\PlanoResource\Pages;

use App\Filament\Resources\PlanoResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePlano extends CreateRecord
{
    protected static string $resource = PlanoResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Plano criado com sucesso!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Garantir que valores nulos sejam tratados corretamente
        if (empty($data['faixa_final'])) {
            $data['faixa_final'] = null;
        }
        
        return $data;
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Plano criado')
            ->body('O plano foi criado e está disponível para uso.')
            ->success()
            ->send();
    }
}
