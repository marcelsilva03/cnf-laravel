<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use App\Models\Faturamento;
use App\Models\APIClient;
use Illuminate\Support\Facades\Auth;

class GerarPagamento extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'API';
    protected static ?string $title = 'Gerar Pagamento';
    protected static ?string $slug = 'gerar-pagamento';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.gerar-pagamento';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'data_pagamento' => now()->format('Y-m-d'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados do Pagamento')
                    ->description('Preencha os dados para gerar um novo pagamento')
                    ->schema([
                        Forms\Components\TextInput::make('valor')
                            ->label('Valor')
                            ->required()
                            ->numeric()
                            ->prefix('R$')
                            ->step(0.01)
                            ->minValue(0.01)
                            ->helperText('Valor do pagamento em reais'),

                        Forms\Components\Select::make('metodo')
                            ->label('Método de Pagamento')
                            ->options([
                                'pix' => 'PIX (5% de desconto)',
                                'cartao' => 'Cartão de Crédito',
                                'boleto' => 'Boleto Bancário (2% de juros)',
                                'transferencia' => 'Transferência Bancária',
                            ])
                            ->required()
                            ->helperText('Escolha o método de pagamento preferido'),

                        Forms\Components\DatePicker::make('data_pagamento')
                            ->label('Data do Pagamento')
                            ->required()
                            ->default(now())
                            ->helperText('Data prevista para o pagamento'),

                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->placeholder('Ex: Pagamento de consumo da API - Janeiro 2025')
                            ->rows(3)
                            ->helperText('Descrição opcional do pagamento'),
                    ]),

                Forms\Components\Section::make('Informações da Conta')
                    ->description('Dados bancários para recebimento')
                    ->schema([
                        Forms\Components\Placeholder::make('banco_info')
                            ->label('Banco')
                            ->content('001 - Banco do Brasil'),

                        Forms\Components\Placeholder::make('agencia_info')
                            ->label('Agência')
                            ->content('2307-8'),

                        Forms\Components\Placeholder::make('conta_info')
                            ->label('Conta Corrente')
                            ->content('198.838-7'),

                        Forms\Components\Placeholder::make('favorecido_info')
                            ->label('Favorecido')
                            ->content('Instituto Bem Viver'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('gerar')
                ->label('Gerar Pagamento')
                ->color('primary')
                ->action('gerar'),
        ];
    }

    public function gerar(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();

        // Verificar se o usuário tem um cliente API configurado
        $apiClient = APIClient::where('user_email', $user->email)->first();
        
        if (!$apiClient) {
            Notification::make()
                ->title('Erro!')
                ->body('Você precisa ter uma chave de API configurada para gerar pagamentos.')
                ->danger()
                ->send();
            return;
        }

        // Aplicar desconto/juros baseado no método
        $valorFinal = $data['valor'];
        switch ($data['metodo']) {
            case 'pix':
                $valorFinal = $data['valor'] * 0.95; // 5% de desconto
                break;
            case 'boleto':
                $valorFinal = $data['valor'] * 1.02; // 2% de juros
                break;
        }

        // Criar o faturamento
        $faturamento = Faturamento::create([
            'user_id' => $user->id,
            'valor' => $valorFinal,
            'metodo' => $data['metodo'],
            'data_pagamento' => $data['data_pagamento'],
            'status' => 'pendente',
            'descricao' => $data['descricao'] ?? 'Pagamento gerado via painel do cliente API',
        ]);

        // Limpar o formulário
        $this->form->fill([
            'valor' => null,
            'metodo' => null,
            'data_pagamento' => now()->format('Y-m-d'),
            'descricao' => null,
        ]);

        Notification::make()
            ->title('Pagamento gerado com sucesso!')
            ->body("Pagamento de R$ " . number_format($valorFinal, 2, ',', '.') . " criado. ID: {$faturamento->id}")
            ->success()
            ->send();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('clienteapi');
    }
} 