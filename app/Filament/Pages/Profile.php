<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Grid;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithFormActions;

    protected static string $view = 'filament.pages.profile';

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'CONFIGURAÇÕES';
    protected static ?string $title = 'Meu Perfil';
    protected static ?string $slug = 'profile';
    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(auth()->user()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações Pessoais')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('E-mail')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),
                    ]),
                Section::make('Alterar Senha')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('current_password')
                                    ->label('Senha Atual')
                                    ->password()
                                    ->required()
                                    ->rule(Password::default()),
                                TextInput::make('new_password')
                                    ->label('Nova Senha')
                                    ->password()
                                    ->required()
                                    ->rule(Password::default()),
                                TextInput::make('new_password_confirmation')
                                    ->label('Confirmar Nova Senha')
                                    ->password()
                                    ->required()
                                    ->same('new_password'),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (isset($data['new_password'])) {
            $data['password'] = Hash::make($data['new_password']);
        }

        auth()->user()->update($data);

        Notification::make()
            ->title('Perfil atualizado com sucesso!')
            ->success()
            ->send();
    }
} 