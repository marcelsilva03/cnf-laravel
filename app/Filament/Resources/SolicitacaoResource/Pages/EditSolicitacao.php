<?php

namespace App\Filament\Resources\SolicitacaoResource\Pages;

use App\Filament\Resources\SolicitacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSolicitacao extends EditRecord
{
    protected static string $resource = SolicitacaoResource::class;


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $updated = parent::handleRecordUpdate($record, $data);
        $updated->sol_status = $data['sol_status'];
        $updated->save();
        return $updated;
    }

    protected function afterSave(): void
    {
        $this->redirect(SolicitacaoResource::getUrl('index'));
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
