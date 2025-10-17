<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitacaoOrcamentoResource\Pages;
use App\Models\SolicitacaoOrcamento;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;

class SolicitacaoOrcamentoResource extends Resource
{
    protected static ?string $model = SolicitacaoOrcamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'COMUNICAÇÕES';
    protected static ?string $modelLabel = 'Solicitação de Orçamento';
    protected static ?string $pluralModelLabel = 'Solicitações de Orçamento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados do Solicitante')
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('telefone')
                            ->label('Telefone')
                            ->tel()
                            ->maxLength(15)
                            ->columnSpan(1),
                    ])
                    ->columns(3),
                    
                Forms\Components\Section::make('Solicitação')
                    ->schema([
                        Forms\Components\Textarea::make('mensagem')
                            ->label('Mensagem')
                            ->required()
                            ->maxLength(65535)
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                SolicitacaoOrcamento::STATUS['PENDENTE'] => 'Pendente',
                                SolicitacaoOrcamento::STATUS['RESPONDIDO'] => 'Respondido',
                                SolicitacaoOrcamento::STATUS['CANCELADO'] => 'Cancelado',
                            ])
                            ->default(SolicitacaoOrcamento::STATUS['PENDENTE'])
                            ->required()
                            ->helperText('Altere o status conforme o andamento da solicitação'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('E-mail copiado!')
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('telefone')
                    ->label('Telefone')
                    ->copyable()
                    ->copyMessage('Telefone copiado!')
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        SolicitacaoOrcamento::STATUS['PENDENTE'] => 'warning',
                        SolicitacaoOrcamento::STATUS['RESPONDIDO'] => 'success',
                        SolicitacaoOrcamento::STATUS['CANCELADO'] => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        SolicitacaoOrcamento::STATUS['PENDENTE'] => 'Pendente',
                        SolicitacaoOrcamento::STATUS['RESPONDIDO'] => 'Respondido',
                        SolicitacaoOrcamento::STATUS['CANCELADO'] => 'Cancelado',
                        default => 'Desconhecido',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data da Solicitação')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->tooltip(fn ($record) => 'Solicitação recebida em ' . $record->created_at->format('d/m/Y \à\s H:i')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        SolicitacaoOrcamento::STATUS['PENDENTE'] => 'Pendente',
                        SolicitacaoOrcamento::STATUS['RESPONDIDO'] => 'Respondido',
                        SolicitacaoOrcamento::STATUS['CANCELADO'] => 'Cancelado',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->tooltip('Visualizar detalhes'),
                Tables\Actions\EditAction::make()
                    ->tooltip('Editar solicitação'),
                Tables\Actions\Action::make('marcar_respondido')
                    ->label('Marcar como Respondido')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Marcar como Respondido')
                    ->modalDescription('Confirma que esta solicitação foi respondida?')
                    ->action(function ($record) {
                        $record->status = SolicitacaoOrcamento::STATUS['RESPONDIDO'];
                        $record->save();
                    })
                    ->visible(fn ($record) => $record->status === SolicitacaoOrcamento::STATUS['PENDENTE'])
                    ->tooltip('Marcar solicitação como respondida'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Excluir Solicitações')
                        ->modalDescription('Tem certeza que deseja excluir as solicitações selecionadas? Esta ação não pode ser desfeita.'),
                    Tables\Actions\BulkAction::make('marcar_respondidas')
                        ->label('Marcar como Respondidas')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Marcar como Respondidas')
                        ->modalDescription('Confirma que as solicitações selecionadas foram respondidas?')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->status = SolicitacaoOrcamento::STATUS['RESPONDIDO'];
                                $record->save();
                            });
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        $roles = ['admin', 'moderador'];
        return $user->hasRole($roles);
    }

    public static function canCreate(): bool
    {
        return false; // Não permitir criar manualmente, apenas através do formulário público
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSolicitacaoOrcamentos::route('/'),
            'view' => Pages\ViewSolicitacaoOrcamento::route('/{record}'),
            'edit' => Pages\EditSolicitacaoOrcamento::route('/{record}/edit'),
        ];
    }
} 