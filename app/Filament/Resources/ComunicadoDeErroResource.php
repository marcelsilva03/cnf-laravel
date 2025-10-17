<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComunicadoDeErroResource\Pages;
use App\Filament\Resources\ComunicadoDeErroResource\RelationManagers;
use App\Models\ComunicadoDeErro;
use App\Models\Falecido;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class ComunicadoDeErroResource extends Resource
{
    protected static ?string $model = ComunicadoDeErro::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'GESTÃO';
    protected static ?string $navigationLabel = 'Comunicação de Erros';
    protected static ?string $pluralModelLabel = 'Comunicações de Erro';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        $isAdmin = auth()->user()->hasRole(['admin', 'moderador']);
        $schema = [];
        
        // Mensagem (campo básico que deve existir)
        $schema[] = Forms\Components\TextInput::make('mensagem')
            ->label('Mensagem')
            ->required()
            ->readOnly(!$isAdmin);
        
        // Campos opcionais que podem não existir na tabela
        if (Schema::hasColumn('comunicados_de_erro', 'email_comunicante')) {
            $schema[] = Forms\Components\TextInput::make('email_comunicante')
                ->label('Email do Comunicante')
                ->email()
                ->required()
                ->readOnly(!$isAdmin);
        }
        
        if (Schema::hasColumn('comunicados_de_erro', 'nome_comunicante')) {
            $schema[] = Forms\Components\TextInput::make('nome_comunicante')
                ->label('Nome do Comunicante')
                ->required()
                ->readOnly(!$isAdmin);
        }
        
        if (Schema::hasColumn('comunicados_de_erro', 'id_falecido')) {
            $schema[] = Forms\Components\Select::make('id_falecido')
                ->label('Falecido')
                ->relationship('falecido', 'fal_nome')
                ->required()
                ->disabled(!$isAdmin);
        }
        
        if (Schema::hasColumn('comunicados_de_erro', 'uuid_falecido')) {
            $schema[] = Forms\Components\TextInput::make('uuid_falecido')
                ->label('UUID do Falecido')
                ->required()
                ->readOnly(!$isAdmin);
        }
        
        if (Schema::hasColumn('comunicados_de_erro', 'status')) {
            $schema[] = Forms\Components\Select::make('status')
                ->label('Status')
                ->options(ComunicadoDeErro::statusList())
                ->default(ComunicadoDeErro::STATUS['PENDENTE'])
                ->required()
                ->disabled(!$isAdmin);
        }
        
        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        $columns = [];
        
        // Adiciona colunas somente se elas existirem na tabela
        if (Schema::hasColumn('comunicados_de_erro', 'nome_comunicante')) {
            $columns[] = Tables\Columns\TextColumn::make('nome_comunicante')
                ->label('Nome do Comunicante');
        }
        
        if (Schema::hasColumn('comunicados_de_erro', 'email_comunicante')) {
            $columns[] = Tables\Columns\TextColumn::make('email_comunicante')
                ->label('Email do Comunicante');
        }
        
        $columns[] = Tables\Columns\TextColumn::make('created_at')
            ->label('Data')
            ->dateTime();
        
        if (Schema::hasColumn('comunicados_de_erro', 'status')) {
            $columns[] = Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn($state) => match ($state) {
                            ComunicadoDeErro::STATUS['PENDENTE'] => 'warning',
                            ComunicadoDeErro::STATUS['APROVADO'] => 'success',
                            ComunicadoDeErro::STATUS['REJEITADO'] => 'danger',
                            default => 'secondary',
                })
                ->formatStateUsing(fn ($state) => ComunicadoDeErro::statusList()[$state] ?? 'Desconhecido');
        }
        
        if (Schema::hasColumn('comunicados_de_erro', 'uuid_falecido')) {
            $columns[] = Tables\Columns\TextColumn::make('uuid_falecido')
                ->label('Nome do Falecido')
                ->formatStateUsing(function ($state) {
                    if (!$state) return 'DESCONHECIDO';
                    
                    $falecido = Falecido::where('fal_uuid', $state)->first();

                    if (!$falecido) {
                        return 'DESCONHECIDO';
                    }
                    return $falecido->fal_nome;
                })
                ->searchable();
        }
        
        $filters = [];
        if (Schema::hasColumn('comunicados_de_erro', 'status')) {
            $filters[] = Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options(ComunicadoDeErro::statusList())
                ->default(ComunicadoDeErro::STATUS['PENDENTE']);
        }
        
        return $table
            ->columns($columns)
            ->filters($filters)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasRole(['admin', 'moderador'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole(['admin'])),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
            $user = auth()->user();
            
                if ($user->hasRole('admin')) {
                    return $query;
                } elseif ($user->hasRole('moderador')) {
                        return $query->where('status', ComunicadoDeErro::STATUS['PENDENTE']);
                    }
        
                return $query;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        $roles = ['admin', 'moderador', 'solicitante'];
        return $user->hasRole($roles);
    }

    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        return $user->hasRole(['admin', 'moderador', 'solicitante']);
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        // Pesquisadores podem criar comunicados de erro
        return $user->hasRole(['pesquisador', 'solicitante']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComunicadoDeErros::route('/'),
            'create' => Pages\CreateComunicadoDeErro::route('/create'),
            'edit' => Pages\EditComunicadoDeErro::route('/{record}/edit'),
        ];
    }
}
