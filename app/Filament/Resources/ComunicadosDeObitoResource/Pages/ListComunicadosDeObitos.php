<?php

namespace App\Filament\Resources\ComunicadosDeObitoResource\Pages;

use App\Filament\Resources\ComunicadosDeObitoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComunicadosDeObitos extends ListRecords
{
    protected static string $resource = ComunicadosDeObitoResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        
        // Remover botão de criação para administradores e moderadores
        // Comunicados de óbito não podem ser criados pelo painel administrativo
        if ($user->hasRole(['admin', 'moderador', 'pesquisador', 'solicitante'])) {
            return [];
        }
        
        return [
            Actions\CreateAction::make(),
        ];
    }
}
