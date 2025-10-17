<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use App\Models\User;
use App\Models\Faturamento;
use App\Models\APIClient;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GestaoClientes extends Page implements HasTable, HasForms, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Gestão de Clientes';
    protected static ?string $title = 'Gestão de Clientes';
    protected static ?string $navigationGroup = 'FINANCEIRO';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.gestao-clientes';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole(['admin', 'financeiro', 'socio-gestor', 'proprietario']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->whereHas('roles', function ($query) {
                        $query->whereIn('name', ['clienteapi', 'solicitante', 'pesquisador']);
                    })
                    ->with(['roles', 'apiClient'])
                    ->withCount(['faturamentos as total_faturamentos'])
                    ->withSum(['faturamentos as valor_total_faturado' => function ($query) {
                        $query->where('status', 'concluido');
                    }], 'valor')
                    ->withSum(['faturamentos as valor_pendente' => function ($query) {
                        $query->where('status', 'pendente');
                    }], 'valor')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('Tipo')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'clienteapi' => 'Cliente API',
                        'solicitante' => 'Solicitante',
                        'pesquisador' => 'Pesquisador',
                        default => ucfirst($state)
                    })
                    ->colors([
                        'primary' => 'clienteapi',
                        'success' => 'solicitante',
                        'info' => 'pesquisador',
                    ]),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('total_faturamentos')
                    ->label('Faturamentos')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('valor_total_faturado')
                    ->label('Total Pago')
                    ->money('BRL')
                    ->sortable()
                    ->default(0),

                Tables\Columns\TextColumn::make('valor_pendente')
                    ->label('Pendente')
                    ->money('BRL')
                    ->sortable()
                    ->default(0)
                    ->color('warning'),

                Tables\Columns\TextColumn::make('apiClient.status')
                    ->label('API')
                    ->formatStateUsing(fn ($state) => $state === 1 ? 'Ativa' : ($state === 0 ? 'Inativa' : 'N/A'))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        1 => 'success',
                        0 => 'danger',
                        default => 'gray'
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Tipo de Cliente')
                    ->relationship('roles', 'name')
                    ->options([
                        'clienteapi' => 'Cliente API',
                        'solicitante' => 'Solicitante',
                        'pesquisador' => 'Pesquisador',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Ativo',
                        0 => 'Inativo',
                    ]),

                Tables\Filters\Filter::make('com_faturamentos')
                    ->label('Com Faturamentos')
                    ->query(fn (Builder $query): Builder => $query->has('faturamentos')),

                Tables\Filters\Filter::make('sem_faturamentos')
                    ->label('Sem Faturamentos')
                    ->query(fn (Builder $query): Builder => $query->doesntHave('faturamentos')),

                Tables\Filters\Filter::make('api_ativa')
                    ->label('API Ativa')
                    ->query(fn (Builder $query): Builder => $query->whereHas('apiClient', function ($q) {
                        $q->where('status', APIClient::STATUS['ATIVO']);
                    })),
            ])
            ->actions([
                Tables\Actions\Action::make('criar_faturamento')
                    ->label('Novo Faturamento')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.resources.faturamentos.create', ['user_id' => $record->id])),

                Tables\Actions\Action::make('ver_faturamentos')
                    ->label('Ver Faturamentos')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->url(fn ($record) => route('filament.admin.resources.faturamentos.index', ['tableFilters[user_id][value]' => $record->id])),

                Tables\Actions\Action::make('editar_cliente')
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->url(fn ($record) => route('filament.admin.resources.users.edit', $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('ativar_clientes')
                    ->label('Ativar Selecionados')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            $record->update(['status' => User::STATUS['ATIVO']]);
                        });
                    })
                    ->requiresConfirmation(),

                Tables\Actions\BulkAction::make('desativar_clientes')
                    ->label('Desativar Selecionados')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            $record->update(['status' => User::STATUS['INATIVO']]);
                        });
                    })
                    ->requiresConfirmation(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('novo_cliente')
                ->label('Novo Cliente')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(route('filament.admin.resources.users.create')),

            Action::make('exportar_relatorio')
                ->label('Exportar Relatório')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->action(function () {
                    // Implementar exportação se necessário
                    $this->notify('success', 'Funcionalidade de exportação será implementada em breve.');
                }),
        ];
    }
} 