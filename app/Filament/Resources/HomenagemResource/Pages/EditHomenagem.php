<?php

namespace App\Filament\Resources\HomenagemResource\Pages;

use App\Filament\Resources\HomenagemResource;
use App\Jobs\ReportaTransacaoViaEmail;
use App\Models\Homenagem;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomenagem extends EditRecord
{
    protected static string $resource = HomenagemResource::class;

    protected function afterSave(): void
    {
        $this->redirect(HomenagemResource::getUrl('index'));
    }
    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        $configMail = config('constants.emails');
        $destinatarios = $configMail['destinatarios'];
        $record = $this->getRecord();
        $statusList = array_flip(Homenagem::STATUS);
        $status = $statusList[$record->hom_status];
        $actionsPerStatus = [
            'PENDENTE' => [
                Actions\Action::make('aprovar')
                    ->label('Aprovar')
                    ->icon('fas-check')
                    ->color('primary')
                    ->action(fn ($record) => $record->update(['hom_status' => Homenagem::STATUS['PUBLICADO']]))
                    ->requiresConfirmation()
                    ->modalHeading('Aprovar homenagem')
                    ->modalDescription('Caso aprovada, a homenagem será publicada e será visível para o público. Deseja aprovar?')
                    ->modalSubmitActionLabel('Aprovar'),
                Actions\Action::make('reprovar')
                    ->label('Reprovar')
                    ->icon('fas-times')
                    ->color('danger')
                    ->action(fn ($record) => $record->update(['hom_status' => Homenagem::STATUS['REMOVIDO']]))
                    ->requiresConfirmation()
                    ->modalHeading('Reprovar homenagem')
                    ->modalDescription('Caso reprovada, a homenagem será removida e a justificativa será enviada para o email do autor. Deseja reprovar?')
                    ->modalSubmitActionLabel('Reprovar'),
            ],
            'PUBLICADO' => [
                Actions\Action::make('remover')
                    ->label('Remover')
                    ->icon('fas-trash')
                    ->color('danger')
                    ->action(function ($record) use ($configMail, $destinatarios) {
                        $record->update(['hom_status' => Homenagem::STATUS['REMOVIDO']]);
                        $dadosEmail = [
                            'envelope' => [
                                'to' => $record->hom_email,
                                'assunto' => 'Homenagem publicada',
                                'cc' => $destinatarios['admin'],
                                'bcc' => $destinatarios['dev']
                            ],
                            'template' => [
                                'view' => $configMail['templates']['homenagem.publicada'],
                                'dados' => [
                                    'nome' => $record->hom_nome_autor,
                                    'url' => route('homenagem.detalhes',
                                        [
                                            'uuid' => $record->hom_uuid_falecido,
                                            'code' => $record->hom_codigo
                                        ]
                                    ),
                                ]
                            ]
                        ];
                        ReportaTransacaoViaEmail::dispatch($dadosEmail);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Remover homenagem')
                    ->modalDescription('Caso removida, a homenagem não estará mais disponível para o público. Deseja remover?')
                    ->modalSubmitActionLabel('Remover'),
            ],
            'REMOVIDO' => [
                Actions\Action::make('recuperar')
                    ->label('Recuperar')
                    ->icon('fas-recycle')
                    ->color('warning')
                    ->action(fn ($record) => $record->update(['hom_status' => Homenagem::STATUS['PUBLICADO']]))
                    ->requiresConfirmation()
                    ->modalHeading('Recuperar homenagem')
                    ->modalDescription('Caso recuperada, a homenagem se tornará disponível ao público novamente. Deseja recuperar?')
                    ->modalSubmitActionLabel('Remover'),
            ]
        ];
        return $user->hasRole(['admin', 'moderador'])
            ? $actionsPerStatus[$status]
            : [];
    }
}
