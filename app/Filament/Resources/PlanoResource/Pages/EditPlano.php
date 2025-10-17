<?php

namespace App\Filament\Resources\PlanoResource\Pages;

use App\Filament\Resources\PlanoResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPlano extends EditRecord
{
    protected static string $resource = PlanoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Excluir Plano')
                ->modalDescription('Tem certeza que deseja excluir este plano? Esta ação não pode ser desfeita.')
                ->modalSubmitActionLabel('Sim, excluir')
                ->successRedirectUrl($this->getResource()::getUrl('index')),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Plano atualizado com sucesso!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Garantir que valores nulos sejam tratados corretamente
        if (empty($data['faixa_final'])) {
            $data['faixa_final'] = null;
        }
        
        return $data;
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Plano atualizado')
            ->body('As alterações foram salvas com sucesso.')
            ->success()
            ->send();
            
        redirect()->to($this->getResource()::getUrl('index'));
    }
}
