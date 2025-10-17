<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('index');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Extrair roles dos dados
        $roles = $data['roles'] ?? [];
        unset($data['roles']);
        
        // Atualizar dados básicos do usuário
        $record->update($data);
        
        // Sincronizar roles (remove todos os atuais e define os novos)
        if (!empty($roles)) {
            $record->syncRoles($roles);
        } else {
            // Se nenhum role foi selecionado, remove todos
            $record->syncRoles([]);
        }
        
        // Log da atualização
        \Log::info('Usuário atualizado com múltiplos perfis', [
            'user_id' => $record->id,
            'user_email' => $record->email,
            'new_roles' => $roles,
            'updated_by' => auth()->user()->email,
            'timestamp' => now()
        ]);
        
        return $record;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Usuário atualizado com sucesso!';
    }

    public function getTitle(): string
    {
        return 'Editar Usuário';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Confirmar Exclusão')
                ->modalDescription('Tem certeza de que deseja excluir este usuário? Esta ação não pode ser desfeita.')
                ->successNotificationTitle('Usuário excluído com sucesso!'),
        ];
    }
}
