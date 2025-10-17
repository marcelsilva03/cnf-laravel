<?php

namespace App\Filament\Resources\FaturamentoResource\Pages;

use App\Filament\Resources\FaturamentoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaturamento extends CreateRecord
{
    protected static string $resource = FaturamentoResource::class;

    public function authorize($ability, $arguments = []): bool
    {
        return auth()->user()->hasRole(['admin', 'financeiro']);
    }
}
