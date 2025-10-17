<?php

namespace App\Filament\Resources\SolicitacaoOrcamentoResource\Pages;

use App\Filament\Resources\SolicitacaoOrcamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditSolicitacaoOrcamento extends EditRecord
{
    protected static string $resource = SolicitacaoOrcamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Excluir Solicitação')
                ->modalDescription('Tem certeza que deseja excluir esta solicitação de orçamento? Esta ação não pode ser desfeita.')
                ->modalSubmitActionLabel('Sim, excluir')
                ->successRedirectUrl($this->getResource()::getUrl('index')),
            Actions\Action::make('cancelar')
                ->label('Cancelar')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Cancelar Edição')
                ->modalDescription('Tem certeza que deseja cancelar? As alterações não salvas serão perdidas.')
                ->modalSubmitActionLabel('Sim, cancelar')
                ->url($this->getResource()::getUrl('index'))
                ->tooltip('Cancelar edição e voltar para a lista'),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Solicitação atualizada!')
            ->body('A solicitação de orçamento foi atualizada com sucesso.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 