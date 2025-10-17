<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        // Após Criar um novo usuário redirecionar para a página de listagem de usuários
        return UserResource::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Extrair roles antes de criar o usuário
        $roles = $data['roles'] ?? [];
        unset($data['roles']);
        
        // Criar o usuário
        $user = static::getModel()::create($data);
        
        // Atribuir roles após a criação
        if (!empty($roles)) {
            $user->assignRole($roles);
        }
        
        // Log da criação
        \Log::info('Usuário criado com múltiplos perfis', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'assigned_roles' => $roles,
            'created_by' => auth()->user()->email,
            'timestamp' => now()
        ]);
        
        return $user;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Usuário criado com sucesso!';
    }
}
