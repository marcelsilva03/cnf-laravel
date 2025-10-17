<?php

namespace App\Filament\Resources\FaturamentoResource\Pages;

use App\Filament\Resources\FaturamentoResource;
use Filament\Resources\Pages\ViewRecord;

class ViewFaturamento extends ViewRecord
{
    protected static string $resource = FaturamentoResource::class;

    protected function getActions(): array
    {
        return []; // No actions available for view page
    }

    protected function getHeaderActions(): array
    {
        return []; // No header actions available for view page
    }
}
