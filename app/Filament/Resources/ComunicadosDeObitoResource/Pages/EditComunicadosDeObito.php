<?php

namespace App\Filament\Resources\ComunicadosDeObitoResource\Pages;

use App\Filament\Resources\ComunicadosDeObitoResource;
use App\Models\ComunicadoDeObito;
use App\Models\Falecido;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditComunicadosDeObito extends EditRecord
{
    const STATUS = [
        'PENDENTE' => 0,
        'ACEITO' => 1,
        'REJEITADO' => 2,
    ];
    protected static string $resource = ComunicadosDeObitoResource::class;

    protected function afterSave(): void
    {
        $this->redirect(ComunicadosDeObitoResource::getUrl('index'));
    }
    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        $actions = [
            'aprovar' => Actions\Action::make('make_falecido_resource')
                    ->label('Confirmar óbito')
                    ->requiresConfirmation()
                    ->modalHeading('Resolver Comunicado de Óbito')
                    ->modalDescription('O Comunicado de Óbito será definido como resolvido e será registrado um Falecido com os dados do Comunicado. Deseja prosseguir?')
                    ->modalSubmitActionLabel('Prosseguir')
                    ->action(function ($record) {
                        $falecido = Falecido::fromComunicadoDeObito($record);
                        $record->update(['status' => self::STATUS['ACEITO']]);
                        Notification::make()
                            ->title('Comunicado de Óbito resolvido')
                            ->success()
                            ->body('O registro foi convertido para Falecido com sucesso.')
                            ->send();
                        return redirect()->route(
                            'filament.dashboard.resources.falecidos.edit',
                            ['record' => $falecido->fal_id]
                        );
                    }),
            'rejeitar' => Actions\Action::make('rejeitar_resource')
                ->label('Rejeitar Comunicado')
                ->icon('fas-times')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Rejeitar Comunicado de Óbito')
                ->modalDescription('Será marcado com rejeitado e não será visível ao público. Deseja rejeitar o comunicado?')
                ->modalSubmitActionLabel('Rejeitar Comunicado')
                ->action(function ($record) {
                    $record->update(['status' => ComunicadoDeObito::STATUS['REJEITADO']]);
                    return redirect()->to(ComunicadosDeObitoResource::getUrl('index'));
                }),
            'pendente' => Actions\Action::make('make_pendente_resource')
                ->label('Marcar como Pendente')
                ->icon('fas-clock')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Marcar Comunicado de Óbito como Pendente')
                ->modalDescription('O Comunicado de Óbito será marcado como pendente. Deseja prosseguir?')
                ->modalSubmitActionLabel('Confirmar')
                ->action(function ($record) {
                    $record->update(['status' => ComunicadoDeObito::STATUS['PENDENTE']]);
                    return redirect()->to(ComunicadosDeObitoResource::getUrl('index'));
                })
            ];
        if ($user->hasRole('admin')
            && $this->record->status == ComunicadoDeObito::STATUS['REJEITADO']
        ) {
            return [$actions['pendente']];
        }
        return [$actions['aprovar'], $actions['rejeitar']];
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('cancel')
                ->label('Voltar')
                ->url($this->previousUrl ?? route('filament.dashboard.resources.comunicados-de-obito.index'))
                ->color('success')
        ];
    }
}
