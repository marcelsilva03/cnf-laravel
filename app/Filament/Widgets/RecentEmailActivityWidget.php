<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\EmailTemplateStatistic;
use Illuminate\Support\Facades\Auth;

class RecentEmailActivityWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        $user = Auth::user();
        
        // Verificar se o usuário existe
        if (!$user) {
            return $table->query(EmailTemplateStatistic::query()->whereRaw('1 = 0')); // Query vazia
        }
        
        $query = EmailTemplateStatistic::query()
            ->with(['template', 'user'])
            ->latest();
            
        if (!$user->hasRole('admin') && !$user->hasRole('proprietario') && !$user->hasRole('socio-gestor')) {
            $query->where('user_id', $user->id);
        }

        return $table
            ->query($query)
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('template.name')
                    ->label('Template')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canView(): bool
    {
        return auth()->check() && auth()->user() !== null;
    }
}
