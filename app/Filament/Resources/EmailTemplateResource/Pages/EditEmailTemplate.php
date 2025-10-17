<?php

namespace App\Filament\Resources\EmailTemplateResource\Pages;

use App\Filament\Resources\EmailTemplateResource;
use App\Models\User;
use App\Notifications\EmailNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Notifications\Notification;

class EditEmailTemplate extends EditRecord
{
    protected static string $resource = EmailTemplateResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('subject')
                            ->label('Assunto')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Use {variable} para substituir valores dinâmicos'),

                        Toggle::make('is_html')
                            ->label('Conteúdo HTML')
                            ->helperText('Marque se o conteúdo for HTML')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->form->fill(['content' => '']);
                            }),

                        RichEditor::make('content')
                            ->label('Conteúdo')
                            ->required()
                            ->helperText('Use {variable} para substituir valores dinâmicos')
                            ->visible(fn (Forms\Get $get): bool => $get('is_html') === false)
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),

                        Textarea::make('content')
                            ->label('Conteúdo HTML')
                            ->required()
                            ->helperText('Use {variable} para substituir valores dinâmicos')
                            ->visible(fn (Forms\Get $get): bool => $get('is_html') === true)
                            ->rows(15)
                            ->columnSpanFull(),

                        FileUpload::make('attachments')
                            ->label('Anexos')
                            ->multiple()
                            ->directory('email-attachments')
                            ->preserveFilenames()
                            ->maxSize(10240)
                            ->helperText('Tamanho máximo: 10MB por arquivo'),
                    ])->columns(1),

                Section::make('Configurações')
                    ->schema([
                        TextInput::make('action_text')
                            ->label('Texto do Botão')
                            ->maxLength(255),

                        TextInput::make('action_url')
                            ->label('URL do Botão')
                            ->maxLength(255)
                            ->helperText('Use {variable} para substituir valores dinâmicos'),

                        KeyValue::make('variables')
                            ->label('Variáveis')
                            ->keyLabel('Nome da variável')
                            ->valueLabel('Descrição')
                            ->keyPlaceholder('Exemplo: nome_usuario')
                            ->valuePlaceholder('Exemplo: Nome completo do usuário')
                            ->addButtonLabel('+ Adicionar')
                            ->dehydrateStateUsing(fn (array $state) => array_filter($state, fn ($key, $value) => $key && $value, ARRAY_FILTER_USE_BOTH))
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Ativo')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Visualizar')
                ->icon('heroicon-o-eye')
                ->modalContent(fn () => view('filament.resources.email-template.pages.preview', [
                    'template' => $this->record,
                    'data' => $this->record->variables ?? [],
                ]))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Fechar'),
                
            Actions\Action::make('test')
                ->label('Enviar Teste')
                ->icon('heroicon-o-paper-airplane')
                ->form([
                    Forms\Components\Select::make('user_id')
                        ->label('Usuário')
                        ->options(User::pluck('name', 'id'))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $user = User::find($data['user_id']);
                    
                    $user->notify(new EmailNotification(
                        $this->record,
                        $this->record->variables ?? []
                    ));
                    
                    Notification::make()
                        ->title('Email de teste enviado')
                        ->success()
                        ->send();
                }),
                
            Actions\DeleteAction::make(),
        ];
    }
} 