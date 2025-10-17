<?php

namespace App\Filament\Resources\SolicitacaoOrcamentoResource\Pages;

use App\Filament\Resources\SolicitacaoOrcamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSolicitacaoOrcamento extends ViewRecord
{
    protected static string $resource = SolicitacaoOrcamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Excluir Solicitação')
                ->modalDescription('Tem certeza que deseja excluir esta solicitação de orçamento? Esta ação não pode ser desfeita.')
                ->modalSubmitActionLabel('Sim, excluir')
                ->successRedirectUrl($this->getResource()::getUrl('index')),
            Actions\Action::make('fechar')
                ->label('Fechar')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->url($this->getResource()::getUrl('index'))
                ->tooltip('Voltar para a lista de solicitações'),
        ];
    }
} 