<?php

namespace App\Filament\Resources\SolicitacaoOrcamentoResource\Pages;

use App\Filament\Resources\SolicitacaoOrcamentoResource;
use App\Models\SolicitacaoOrcamento;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSolicitacaoOrcamentos extends ListRecords
{
    protected static string $resource = SolicitacaoOrcamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('ajuda')
                ->label('Ajuda')
                ->icon('heroicon-o-question-mark-circle')
                ->color('info')
                ->modalHeading('Como gerenciar Solicitações de Orçamento')
                ->modalDescription('
                    • As solicitações são criadas automaticamente pelo formulário público
                    • Use os filtros para encontrar solicitações específicas
                    • Marque como "Respondido" após dar retorno ao cliente
                    • Use a ação em lote para processar múltiplas solicitações
                ')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Fechar'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todas')
                ->badge(SolicitacaoOrcamento::count()),
            'pendentes' => Tab::make('Pendentes')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', SolicitacaoOrcamento::STATUS['PENDENTE']))
                ->badge(SolicitacaoOrcamento::where('status', SolicitacaoOrcamento::STATUS['PENDENTE'])->count())
                ->badgeColor('warning'),
            'respondidas' => Tab::make('Respondidas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', SolicitacaoOrcamento::STATUS['RESPONDIDO']))
                ->badge(SolicitacaoOrcamento::where('status', SolicitacaoOrcamento::STATUS['RESPONDIDO'])->count())
                ->badgeColor('success'),
            'canceladas' => Tab::make('Canceladas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', SolicitacaoOrcamento::STATUS['CANCELADO']))
                ->badge(SolicitacaoOrcamento::where('status', SolicitacaoOrcamento::STATUS['CANCELADO'])->count())
                ->badgeColor('danger'),
        ];
    }
} 