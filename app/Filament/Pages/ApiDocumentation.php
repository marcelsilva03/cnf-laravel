<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ApiDocumentation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'API';
    
    protected static ?string $title = 'Documentação';
    
    protected static ?string $slug = 'documentacao-api';
    
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.api-documentation';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('clienteapi');
    }
} 