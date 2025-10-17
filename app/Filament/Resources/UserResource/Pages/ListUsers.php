<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Usuários';
    }
    protected function getHeaderActions(): array
    {
        if (auth()->user()->hasRole('admin')) {
            return [

                Actions\CreateAction::make()
                ->icon('heroicon-s-user-plus')
                ->color('primary')
                ->label('Criar usuário')
            ];
        }
        return [];
    }
}
