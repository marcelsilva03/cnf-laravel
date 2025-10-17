<?php

namespace App\Filament\Resources\EmailContatoResource\Pages;

use App\Filament\Resources\EmailContatoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailContatos extends ListRecords
{
    protected static string $resource = EmailContatoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Sem a ação de criar, pois os e-mails só podem ser enviados pelo formulário de contato
        ];
    }
} 