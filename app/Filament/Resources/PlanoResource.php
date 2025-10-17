<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanoResource\Pages;
use App\Models\Plano;
use App\Rules\PlanoFaixaValidation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PlanoResource extends Resource
{
    protected static ?string $model = Plano::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'FINANCEIRO';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return !$user->hasRole('moderador');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('faixa_inicial')
                    ->label('Faixa Inicial')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->helperText('Valor inicial da faixa (não pode ser negativo)')
                    ->rules([
                        function () {
                            return new PlanoFaixaValidation('faixa_inicial', request()->route('record'));
                        }
                    ])
                    ->validationMessages([
                        'min' => 'A faixa inicial deve ser um valor positivo.',
                        'numeric' => 'A faixa inicial deve ser um número válido.',
                        'required' => 'A faixa inicial é obrigatória.',
                    ]),
                Forms\Components\TextInput::make('faixa_final')
                    ->label('Faixa Final')
                    ->numeric()
                    ->minValue(0)
                    ->helperText('Valor final da faixa (deixe vazio para faixa ilimitada)')
                    ->rules([
                        function () {
                            return new PlanoFaixaValidation('faixa_final', request()->route('record'));
                        }
                    ])
                    ->validationMessages([
                        'min' => 'A faixa final deve ser um valor positivo.',
                        'numeric' => 'A faixa final deve ser um número válido.',
                    ]),
                Forms\Components\TextInput::make('preco_por_consulta')
                    ->label('Preço por Consulta')
                    ->required()
                    ->numeric()
                    ->step(0.0001)
                    ->minValue(0)
                    ->helperText('Valor cobrado por consulta nesta faixa')
                    ->validationMessages([
                        'min' => 'O preço deve ser um valor positivo.',
                        'numeric' => 'O preço deve ser um número válido.',
                        'required' => 'O preço por consulta é obrigatório.',
                    ]),
                Forms\Components\Toggle::make('ativo')
                    ->label('Plano Ativo')
                    ->required()
                    ->helperText('Define se este plano está ativo para uso'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('faixa_inicial')
                    ->label('Faixa Inicial')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('faixa_final')
                    ->label('Faixa Final')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 0, ',', '.') : 'Ilimitada')
                    ->sortable(),
                Tables\Columns\TextColumn::make('faixa_descricao')
                    ->label('Descrição da Faixa')
                    ->formatStateUsing(function ($record) {
                        $inicial = number_format($record->faixa_inicial, 0, ',', '.');
                        $final = $record->faixa_final ? number_format($record->faixa_final, 0, ',', '.') : '∞';
                        return "{$inicial} - {$final}";
                    })
                    ->searchable(false)
                    ->sortable(false),
                Tables\Columns\TextColumn::make('preco_por_consulta')
                    ->label('Preço por Consulta')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Status')
                    ->placeholder('Todos os planos')
                    ->trueLabel('Apenas ativos')
                    ->falseLabel('Apenas inativos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('Excluir')
                    ->requiresConfirmation()
                    ->modalHeading('Excluir Plano')
                    ->modalDescription('Tem certeza que deseja excluir este plano? Esta ação não pode ser desfeita.')
                    ->modalSubmitActionLabel('Sim, excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Excluir selecionados')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('faixa_inicial', 'asc')
            ->striped()
            ->emptyStateHeading('Nenhum plano cadastrado')
            ->emptyStateDescription('Comece criando o primeiro plano do sistema.')
            ->emptyStateIcon('heroicon-o-rectangle-stack');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlanos::route('/'),
            'create' => Pages\CreatePlano::route('/create'),
            'edit' => Pages\EditPlano::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        $roles = ['admin'];
        return $user->hasRole($roles);
    }
    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        $roles = ['admin'];
        return $user->hasRole($roles);
    }
}
