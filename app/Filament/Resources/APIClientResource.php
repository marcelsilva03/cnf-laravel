<?php

namespace App\Filament\Resources;

use App\Filament\Resources\APIClientResource\Pages;
use App\Filament\Resources\APIClientResource\RelationManagers;
use App\Models\APIClient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentJavaScript;

class APIClientResource extends Resource
{
    protected static ?string $model = APIClient::class;

    protected static ?string $navigationIcon = 'fas-key';
    protected static ?string $navigationLabel = 'Client Key';
    protected static ?string $navigationGroup = 'API';

    protected static ?string $label = 'Client Key';
    protected static ?string $pluralLabel = 'Client Key';

    public static function form(Form $form): Form
    {
        $isEdit = str_contains(request()->path(), '/edit');
        $schema = [
            Forms\Components\TextInput::make('name')
                ->label('Nome')
                ->required()
                ->readOnly($isEdit),
            Forms\Components\TextInput::make('api_key')
                ->label('Chave API')
                ->required()
                ->readOnly()
                ->visible($isEdit)
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('request_limit')
                ->label('Limite de Requisições')
                ->numeric()
                ->readOnly()
                ->visible($isEdit)
                ->required(),
            Forms\Components\TextInput::make('requests_made')
                ->label('Requisições realizadas')
                ->numeric()
                ->readOnly()
                ->visible($isEdit)
                ->required(),
            Forms\Components\Toggle::make('status')
                ->label('Chave Ativa')
                ->visible($isEdit),
        ];
        return $form
            ->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('api_key')
                    ->label('Chave')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Chave copiada!')
                    ->copyMessageDuration(1500)
                    ->copyableState(fn (APIClient $record): string => $record->api_key),
                Tables\Columns\TextColumn::make('request_limit')
                    ->label('Limite')
                    ->sortable(),
                Tables\Columns\TextColumn::make('requests_made')
                    ->label('Uso')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        APIClient::STATUS['ATIVO'] => 'success',
                        APIClient::STATUS['INATIVO'] => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn($state) => array_flip(APIClient::STATUS)[$state]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(array_flip(APIClient::STATUS)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->role_id === 1), // Only admin can edit
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->role_id === 5 || auth()->user()->role_id === 1), // clienteapi and admin can delete
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('desativar')
                        ->label('Desativar')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $record->update(['status' => APIClient::STATUS['INATIVO']]);
                            });
                        })
                        ->visible(fn () => auth()->user()->role_id === 1), // Only admin can bulk deactivate
                ]),
            ]);
    }


    public static function canViewAny(): bool
    {
        $user = auth()->user();
        $roles = ['admin', 'clienteapi', 'financeiro'];
        return $user->hasRole($roles);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAPIClients::route('/'),
            'create' => Pages\CreateAPIClient::route('/create'),
            'edit' => Pages\EditAPIClient::route('/{record}/edit'),
        ];
    }
}
