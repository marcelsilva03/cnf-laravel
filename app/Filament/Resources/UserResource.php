<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $breadcrumb = 'Usuários';
    protected static ?string $navigationGroup = 'ADMINISTRATIVO';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->required(fn($livewire) => $livewire instanceof Pages\CreateUser)
                    ->dehydrated(fn($state) => !empty($state))
                    ->hiddenOn('edit'),
                Forms\Components\Select::make('roles')
                    ->label('Perfis de Acesso')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->options(function () {
                        try {
                            $roles = Role::all()->pluck('name', 'name');
                            
                            // Se não há roles no banco, criar os padrão
                            if ($roles->isEmpty()) {
                                $defaultRoles = [
                                    'admin' => 'admin',
                                    'pesquisador' => 'pesquisador', 
                                    'moderador' => 'moderador',
                                    'financeiro' => 'financeiro',
                                    'clienteapi' => 'clienteapi',
                                    'socio-gestor' => 'socio-gestor',
                                    'proprietario' => 'proprietario',
                                    'solicitante' => 'solicitante',
                                ];
                                
                                // Criar roles se não existem
                                foreach ($defaultRoles as $roleName) {
                                    Role::firstOrCreate(
                                        ['name' => $roleName],
                                        ['guard_name' => 'web']
                                    );
                                }
                                
                                return $defaultRoles;
                            }
                            
                            return $roles->toArray();
                        } catch (\Exception $e) {
                            // Fallback em caso de erro
                            \Log::error('Erro ao carregar roles no UserResource: ' . $e->getMessage());
                            return [
                                'admin' => 'admin',
                                'pesquisador' => 'pesquisador', 
                                'moderador' => 'moderador',
                                'financeiro' => 'financeiro',
                                'clienteapi' => 'clienteapi',
                                'socio-gestor' => 'socio-gestor',
                                'proprietario' => 'proprietario',
                                'solicitante' => 'solicitante',
                            ];
                        }
                    })
                    ->preload()
                    ->required()
                    ->helperText('Selecione um ou mais perfis. O usuário terá as permissões de todos os perfis selecionados.')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('status')
                    ->label('Ativo (pode fazer login)')
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => $state === 1 ? 'success' : 'danger')
                    ->formatStateUsing(fn($state) => $state === 1 ? 'ATIVO' : 'INATIVO')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower(trim($search));
                
                        if ($search === 'ativo') {
                            return $query->where('status', 1);
                        } elseif ($search === 'inativo') {
                            return $query->where('status', 0);
                        }
                
                        return $query->where(function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('roles_display')
                    ->label('Perfis de Acesso')
                    ->getStateUsing(function (User $record): string {
                        $roles = $record->roles->pluck('name')->toArray();
                        if (empty($roles)) {
                            return 'Nenhum perfil';
                        }
                        return implode(', ', $roles);
                    })
                    ->wrap()
                    ->badge()
                    ->separator(',')
                    ->color(function (User $record) {
                        $roleNames = $record->roles->pluck('name')->toArray();
                        if (in_array('admin', $roleNames)) return 'danger';
                        if (in_array('socio-gestor', $roleNames)) return 'primary';
                        if (in_array('proprietario', $roleNames)) return 'info';
                        if (array_intersect(['moderador', 'pesquisador', 'financeiro'], $roleNames)) return 'warning';
                        return 'success';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('roles', function (Builder $roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', "%{$search}%");
                        });
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Filtrar por Perfil')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Ativo',
                        0 => 'Inativo',
                    ])
                    ->attribute('status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(''),
                Tables\Actions\DeleteAction::make()
                    ->label(''),
                Tables\Actions\Action::make('gerenciar_perfis')
                    ->label('')
                    ->icon('heroicon-o-user-group')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('roles_to_add')
                            ->label('Adicionar Perfis')
                            ->multiple()
                            ->options(function (User $record) {
                                $currentRoles = $record->roles->pluck('name')->toArray();
                                return Role::whereNotIn('name', $currentRoles)->pluck('name', 'name');
                            })
                            ->helperText('Selecione os perfis que deseja adicionar ao usuário'),
                        Forms\Components\Select::make('roles_to_remove')
                            ->label('Remover Perfis')
                            ->multiple()
                            ->options(function (User $record) {
                                return $record->roles->pluck('name', 'name');
                            })
                            ->helperText('Selecione os perfis que deseja remover do usuário'),
                    ])
                    ->action(function (User $record, array $data) {
                        // Adicionar novos roles
                        if (!empty($data['roles_to_add'])) {
                            foreach ($data['roles_to_add'] as $roleName) {
                                $record->assignRole($roleName);
                            }
                        }
                        
                        // Remover roles selecionados
                        if (!empty($data['roles_to_remove'])) {
                            foreach ($data['roles_to_remove'] as $roleName) {
                                $record->removeRole($roleName);
                            }
                        }
                        
                        // Log da ação
                        \Log::info('Perfis de usuário alterados', [
                            'user_id' => $record->id,
                            'user_email' => $record->email,
                            'roles_added' => $data['roles_to_add'] ?? [],
                            'roles_removed' => $data['roles_to_remove'] ?? [],
                            'current_roles' => $record->fresh()->roles->pluck('name')->toArray(),
                            'changed_by' => auth()->user()->email,
                            'timestamp' => now()
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Gerenciar Perfis do Usuário')
                    ->modalDescription('Adicione ou remova perfis deste usuário. As alterações serão aplicadas imediatamente.')
                    ->modalSubmitActionLabel('Aplicar Alterações')
                    ->successNotificationTitle('Perfis atualizados!')
                    ->successNotification(
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Perfis atualizados!')
                            ->body('Os perfis do usuário foram alterados com sucesso.')
                    ),
                Tables\Actions\Action::make('trocar_para_socio_gestor')
                    ->label('')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->visible(function (User $record) {
                        return $record->hasRole('proprietario') && !$record->hasRole('socio-gestor');
                    })
                    ->action(function (User $record) {
                        $record->removeRole('proprietario');
                        $record->assignRole('socio-gestor');
                        
                        \Log::info('Perfil alterado de Proprietário para Sócio-Gestor', [
                            'user_id' => $record->id,
                            'user_email' => $record->email,
                            'changed_by' => auth()->user()->email,
                            'timestamp' => now()
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar Mudança de Perfil')
                    ->modalDescription('Tem certeza de que deseja alterar o perfil deste usuário de "Proprietário" para "Sócio-Gestor"?')
                    ->modalSubmitActionLabel('Confirmar Mudança')
                    ->successNotificationTitle('Perfil alterado com sucesso!')
                    ->successNotification(
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Perfil alterado!')
                            ->body('O usuário foi alterado de Proprietário para Sócio-Gestor com sucesso.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('assign_role_bulk')
                        ->label('Atribuir Perfil em Massa')
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->form([
                            Forms\Components\Select::make('role')
                                ->label('Perfil para Atribuir')
                                ->options(Role::all()->pluck('name', 'name'))
                                ->required()
                                ->helperText('Este perfil será adicionado a todos os usuários selecionados'),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data) {
                            foreach ($records as $record) {
                                if (!$record->hasRole($data['role'])) {
                                    $record->assignRole($data['role']);
                                }
                            }
                            
                            \Log::info('Perfil atribuído em massa', [
                                'role' => $data['role'],
                                'user_count' => $records->count(),
                                'user_ids' => $records->pluck('id')->toArray(),
                                'changed_by' => auth()->user()->email,
                                'timestamp' => now()
                            ]);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Atribuir Perfil em Massa')
                        ->modalDescription(fn (\Illuminate\Database\Eloquent\Collection $records) => 
                            'Tem certeza de que deseja atribuir o perfil selecionado aos ' . $records->count() . ' usuários selecionados?'
                        )
                        ->successNotificationTitle('Perfil atribuído com sucesso!')
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return $query;
        }
        return $query->where('id', $user->id);
    }

    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return true;
        }
        return $record->id === $user->id;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
