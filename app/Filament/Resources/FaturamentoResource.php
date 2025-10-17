<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaturamentoResource\Pages;
use App\Models\Faturamento;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FaturamentoResource extends Resource
{
    protected static ?string $navigationLabel = 'Faturamentos';
    protected static ?string $navigationGroup = 'FINANCEIRO';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return !$user->hasRole('moderador');
    }

    public static function getTitle(): string
    {
        return 'Faturamentos';
    }

    public static function getPluralLabel(): string
    {
        return 'Faturamentos';
    }

    protected static ?string $model = Faturamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        $isFinanceiroOrAdmin = auth()->user()->hasRole(['admin', 'financeiro']);
        
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Cliente')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->visible($isFinanceiroOrAdmin)
                    ->options(function () {
                        return User::whereHas('roles', function ($query) {
                            $query->whereIn('name', ['clienteapi', 'solicitante', 'pesquisador']);
                        })
                        ->where('status', User::STATUS['ATIVO'])
                        ->orderBy('name')
                        ->get()
                        ->mapWithKeys(function ($user) {
                            $roleDisplayName = match($user->roles->first()?->name) {
                                'clienteapi' => 'Cliente API',
                                'solicitante' => 'Solicitante',
                                'pesquisador' => 'Pesquisador',
                                default => 'N/A'
                            };
                            return [$user->id => "{$user->name} ({$user->email}) - {$roleDisplayName}"];
                        });
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        return User::where(function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->whereHas('roles', function ($query) {
                            $query->whereIn('name', ['clienteapi', 'solicitante', 'pesquisador']);
                        })
                        ->where('status', User::STATUS['ATIVO'])
                        ->orderBy('name')
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(function ($user) {
                            $roleDisplayName = match($user->roles->first()?->name) {
                                'clienteapi' => 'Cliente API',
                                'solicitante' => 'Solicitante',
                                'pesquisador' => 'Pesquisador',
                                default => 'N/A'
                            };
                            return [$user->id => "{$user->name} ({$user->email}) - {$roleDisplayName}"];
                        });
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique('users', 'email')
                            ->maxLength(255),
                        Forms\Components\Select::make('role')
                            ->label('Tipo de Cliente')
                            ->options([
                                'clienteapi' => 'Cliente API',
                                'solicitante' => 'Solicitante',
                                'pesquisador' => 'Pesquisador',
                            ])
                            ->required()
                            ->default('solicitante'),
                        Forms\Components\Toggle::make('status')
                            ->label('Ativo')
                            ->default(true),
                    ])
                    ->createOptionUsing(function (array $data) {
                        // Validar se email já existe
                        if (User::where('email', $data['email'])->exists()) {
                            throw new \Exception('Email já está em uso por outro usuário.');
                        }
                        
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => bcrypt(Str::random(12)),
                            'status' => $data['status'] ? User::STATUS['ATIVO'] : User::STATUS['INATIVO'],
                        ]);
                        
                        $user->assignRole($data['role']);
                        
                        // Log da criação
                        \Log::info('Novo cliente criado via faturamento', [
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => $data['role'],
                            'created_by' => auth()->id()
                        ]);
                        
                        return $user->id;
                    })
                    ->hint('🔍 Busque por nome ou email. Apenas clientes ativos são exibidos.')
                    ->helperText('Você pode criar um novo cliente clicando no botão "+" se não encontrar na lista.'),
                    
                Forms\Components\TextInput::make('valor')
                    ->label('Valor')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->step(0.01),
                    
                Forms\Components\Select::make('metodo')
                    ->label('Método de Pagamento')
                    ->options([
                        'pix' => 'PIX',
                        'cartao' => 'Cartão de Crédito',
                        'boleto' => 'Boleto Bancário',
                        'transferencia' => 'Transferência Bancária',
                    ])
                    ->required()
                    ->visible($isFinanceiroOrAdmin),
                    
                Forms\Components\DatePicker::make('data_pagamento')
                    ->label('Data do Pagamento')
                    ->required()
                    ->default(now())
                    ->displayFormat('d/m/Y')
                    ->format('Y-m-d')
                    ->native(false),
                    
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pendente' => 'Pendente',
                        'concluido' => 'Concluído',
                        'cancelado' => 'Cancelado',
                    ])
                    ->default('pendente')
                    ->required()
                    ->visible($isFinanceiroOrAdmin),
                    
                Forms\Components\Textarea::make('descricao')
                    ->label('Descrição')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        $isFinanceiroOrAdmin = $user->hasRole(['admin', 'financeiro']);
        
        // Se for financeiro ou admin, mostra todos os registros, senão apenas do usuário logado
        $query = $isFinanceiroOrAdmin 
            ? Faturamento::query() 
            : Faturamento::query()->where('user_id', $user->id);
            
        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable()
                    ->visible($isFinanceiroOrAdmin),
                    
                Tables\Columns\TextColumn::make('valor')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('metodo')
                    ->label('Método de Pagamento')
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
                    })
                    ->sortable(),
                    
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
                    })
                    ->sortable()
                    ->visible($isFinanceiroOrAdmin),
                    
                Tables\Columns\TextColumn::make('data_pagamento')
                    ->label('Data do Pagamento')
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
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('data_pagamento', 'desc')
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
                    ])
                    ->visible($isFinanceiroOrAdmin),
                    
                Tables\Filters\Filter::make('data_pagamento')
                    ->form([
                        Forms\Components\DatePicker::make('data_de')
                            ->label('Data de')
                            ->displayFormat('d/m/Y')
                            ->format('Y-m-d')
                            ->native(false),
                        Forms\Components\DatePicker::make('data_ate')
                            ->label('Data até')
                            ->displayFormat('d/m/Y')
                            ->format('Y-m-d')
                            ->native(false),
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
                    
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Cliente')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->visible($isFinanceiroOrAdmin),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => $isFinanceiroOrAdmin),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('admin')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaturamento::route('/'),
            'create' => Pages\CreateFaturamento::route('/create'),
            'edit' => Pages\EditFaturamento::route('/{record}/edit'),
            'view' => Pages\ViewFaturamento::route('/{record}/view'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user->hasRole(['admin', 'financeiro', 'clienteapi']);
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user->hasRole(['admin', 'financeiro']);
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();
        return $user->hasRole(['admin', 'financeiro']);
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();
        return $user->hasRole('admin');
    }

    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        
        // Admin e financeiro podem ver todos
        if ($user->hasRole(['admin', 'financeiro'])) {
            return true;
        }
        
        // Outros usuários só podem ver seus próprios registros
        return $record->user_id === $user->id;
    }
}
