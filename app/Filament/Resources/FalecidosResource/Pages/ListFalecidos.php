<?php

namespace App\Filament\Resources\FalecidosResource\Pages;

use App\Filament\Resources\FalecidosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListFalecidos extends ListRecords
{
    protected static string $resource = FalecidosResource::class;

    public function getTitle(): string|Htmlable
    {
        return parent::getTitle();
    }

    protected function getHeaderActions(): array
    {
        // Remover botão de criação para administradores
        $user = auth()->user();
        if ($user && $user->hasRole('admin')) {
            return [];
        }
        
        return [
            Actions\CreateAction::make(),
        ];
    }
}
