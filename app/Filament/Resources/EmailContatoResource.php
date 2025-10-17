<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailContatoResource\Pages;
use App\Models\EmailContato;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;

class EmailContatoResource extends Resource
{
    protected static ?string $model = EmailContato::class;

    protected static ?string $navigationIcon = 'fas-envelope';
    protected static ?string $navigationGroup = 'COMUNICAÇÕES';
    protected static ?string $modelLabel = 'E-mail de Contato';
    protected static ?string $pluralModelLabel = 'E-mails de Contato';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefone')
                    ->label('Telefone')
                    ->tel()
                    ->maxLength(15),
                Forms\Components\TextInput::make('assunto')
                    ->label('Assunto')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('mensagem')
                    ->label('Mensagem')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefone')
                    ->label('Telefone'),
                Tables\Columns\TextColumn::make('assunto')
                    ->label('Assunto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data de Envio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
        return false; // Não permitir criar manualmente, apenas através do formulário de contato
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailContatos::route('/'),
            'view' => Pages\ViewEmailContato::route('/{record}'),
        ];
    }
} 