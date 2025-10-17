<?php

namespace App\Filament\Resources\EmailContatoResource\Pages;

use App\Filament\Resources\EmailContatoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmailContato extends ViewRecord
{
    protected static string $resource = EmailContatoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 