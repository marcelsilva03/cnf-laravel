<?php

namespace App\Filament\Resources\ComunicadoDeErroResource\Pages;

use App\Filament\Resources\ComunicadoDeErroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComunicadoDeErros extends ListRecords
{
    protected static string $resource = ComunicadoDeErroResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        
        // Remover botão de criação para administradores e moderadores
        // Somente pesquisadores e solicitantes podem criar comunicados de erro
        if ($user->hasRole(['admin', 'moderador'])) {
            return [];
        }
        
        return [
            Actions\CreateAction::make(),
        ];
    }
}
