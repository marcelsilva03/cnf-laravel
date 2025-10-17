<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;
use App\Models\Faturamento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class HistoricoPagamentos extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'API';
    protected static ?string $title = 'Histórico de Pagamentos';
    protected static ?string $slug = 'historico-pagamentos';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.historico-pagamentos';

    public function table(Table $table): Table
    {
        return $table
            ->query(Faturamento::query()->where('user_id', Auth::id()))
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('valor')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),

                Tables\Columns\TextColumn::make('metodo')
                    ->label('Método')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pix' => 'PIX',
                        'cartao' => 'Cartão',
                        'boleto' => 'Boleto',
                        'transferencia' => 'Transferência',
                        default => ucfirst($state)
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pix' => 'success',
                        'cartao' => 'info',
                        'boleto' => 'warning',
                        'transferencia' => 'primary',
                        default => 'gray'
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pendente' => 'Pendente',
                        'concluido' => 'Concluído',
                        'cancelado' => 'Cancelado',
                        default => ucfirst($state)
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pendente' => 'warning',
                        'concluido' => 'success',
                        'cancelado' => 'danger',
                        default => 'gray'
                    }),

                Tables\Columns\TextColumn::make('data_pagamento')
                    ->label('Data Pagamento')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('metodo')
                    ->label('Método de Pagamento')
                    ->options([
                        'pix' => 'PIX',
                        'cartao' => 'Cartão',
                        'boleto' => 'Boleto',
                        'transferencia' => 'Transferência',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pendente' => 'Pendente',
                        'concluido' => 'Concluído',
                        'cancelado' => 'Cancelado',
                    ]),

                Tables\Filters\Filter::make('data_pagamento')
                    ->form([
                        Forms\Components\DatePicker::make('data_de')
                            ->label('Data de'),
                        Forms\Components\DatePicker::make('data_ate')
                            ->label('Data até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['data_de'],
                                fn (Builder $query, $date): Builder => $query->whereDate('data_pagamento', '>=', $date),
                            )
                            ->when(
                                $data['data_ate'],
                                fn (Builder $query, $date): Builder => $query->whereDate('data_pagamento', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Visualizar')
                    ->modalHeading('Detalhes do Pagamento')
                    ->modalContent(function ($record) {
                        return view('filament.modals.pagamento-details', ['record' => $record]);
                    }),
            ])
            ->emptyStateHeading('Nenhum pagamento encontrado')
            ->emptyStateDescription('Você ainda não possui histórico de pagamentos.')
            ->emptyStateIcon('heroicon-o-credit-card');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('clienteapi');
    }
} 