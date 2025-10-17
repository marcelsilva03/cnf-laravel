<?php

namespace App\Filament\Resources\HomenagemResource\Pages;

use App\Filament\Resources\HomenagemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomenagems extends ListRecords
{
    protected static string $resource = HomenagemResource::class;

    public function getTitle(): string
    {
        return 'Homenagens';
    }
    
    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        
        // Verificação segura - usar Spatie Permission
        if ($user && $user->hasRole('admin')) {
            return [];
        }
        
        return [
            Actions\CreateAction::make(),
        ];
    }
}
