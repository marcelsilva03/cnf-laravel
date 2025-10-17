<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailTemplateResource\Pages;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Notifications\EmailNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'ADMINISTRATIVO';

    protected static ?string $modelLabel = 'Template de Email';

    protected static ?string $pluralModelLabel = 'Templates de Email';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('subject')
                        ->label('Assunto')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Use {variable} para substituir valores dinâmicos'),

                    Forms\Components\Toggle::make('is_html')
                        ->label('Conteúdo HTML')
                        ->helperText('Marque se o conteúdo for HTML')
                        ->default(false),

                    Forms\Components\RichEditor::make('content')
                        ->label('Conteúdo')
                        ->required()
                        ->helperText('Use {variable} para substituir valores dinâmicos')
                        ->visible(fn (Forms\Get $get) => !$get('is_html')),

                    Forms\Components\Textarea::make('content')
                        ->label('Conteúdo HTML')
                        ->required()
                        ->helperText('Use {variable} para substituir valores dinâmicos')
                        ->visible(fn (Forms\Get $get) => $get('is_html')),

                    Forms\Components\FileUpload::make('attachments')
                        ->label('Anexos')
                        ->multiple()
                        ->directory('email-attachments')
                        ->preserveFilenames()
                        ->maxSize(10240)
                        ->helperText('Tamanho máximo: 10MB por arquivo'),
                ])->columns(1),

            Forms\Components\Section::make('Configurações')
                ->schema([
                    Forms\Components\TextInput::make('action_text')
                        ->label('Texto do Botão')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('action_url')
                        ->label('URL do Botão')
                        ->maxLength(255)
                        ->helperText('Use {variable} para substituir valores dinâmicos'),

                    Forms\Components\KeyValue::make('variables')
                        ->label('Variáveis')
                        ->helperText('Defina as variáveis disponíveis para este template'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Ativo')
                        ->default(true),
                ])->columns(2),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(static::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Assunto')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_html')
                    ->label('HTML')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),

                Tables\Columns\TextColumn::make('stats.sent')
                    ->label('Enviados')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->withCount(['statistics as sent_count' => function ($query) {
                            $query->where('type', 'sent');
                        }])->orderBy('sent_count', $direction);
                    }),

                Tables\Columns\TextColumn::make('stats.open_rate')
                    ->label('Taxa de Abertura')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->withCount(['statistics as sent_count' => function ($query) {
                            $query->where('type', 'sent');
                        }, 'statistics as opened_count' => function ($query) {
                            $query->where('type', 'opened');
                        }])->orderByRaw('(opened_count / NULLIF(sent_count, 0)) * 100 ' . $direction);
                    }),

                Tables\Columns\TextColumn::make('stats.click_rate')
                    ->label('Taxa de Clique')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->withCount(['statistics as sent_count' => function ($query) {
                            $query->where('type', 'sent');
                        }, 'statistics as clicked_count' => function ($query) {
                            $query->where('type', 'clicked');
                        }])->orderByRaw('(clicked_count / NULLIF(sent_count, 0)) * 100 ' . $direction);
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Ativo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailTemplates::route('/'),
            'create' => Pages\CreateEmailTemplate::route('/create'),
            'edit' => Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }
}
