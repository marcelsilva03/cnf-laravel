<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Storage;

class ConfiguracaoEFI extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'API';
    protected static ?string $title = 'Configuração EFI Pay';
    protected static ?string $slug = 'configuracao-efi';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.configuracao-efi';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getConfigData());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Configurações EFI Pay')
                    ->description('Configure os parâmetros de integração com a EFI Pay (Gerencianet)')
                    ->schema([
                        Forms\Components\TextInput::make('client_id')
                            ->label('Client ID')
                            ->required()
                            ->placeholder('Client_Id_...')
                            ->helperText('Identificador do cliente fornecido pela EFI Pay'),

                        Forms\Components\TextInput::make('client_secret')
                            ->label('Client Secret')
                            ->required()
                            ->password()
                            ->placeholder('Client_Secret_...')
                            ->helperText('Chave secreta fornecida pela EFI Pay'),

                        Forms\Components\Toggle::make('sandbox')
                            ->label('Modo Sandbox')
                            ->helperText('Ativar para testes (ambiente de desenvolvimento)')
                            ->default(true),

                        Forms\Components\FileUpload::make('certificado')
                            ->label('Certificado (.p12)')
                            ->acceptedFileTypes(['application/x-pkcs12'])
                            ->directory('efi-certificates')
                            ->helperText('Arquivo de certificado fornecido pela EFI Pay'),

                        Forms\Components\TextInput::make('webhook_url')
                            ->label('URL de Webhook')
                            ->url()
                            ->placeholder('https://seudominio.com/webhook/efi')
                            ->helperText('URL para receber notificações de pagamento'),
                    ]),

                Forms\Components\Section::make('Configurações de Pagamento')
                    ->schema([
                        Forms\Components\Toggle::make('cartao_ativo')
                            ->label('Cartão de Crédito')
                            ->helperText('Habilitar pagamentos via cartão de crédito')
                            ->default(true),

                        Forms\Components\Toggle::make('boleto_ativo')
                            ->label('Boleto Bancário')
                            ->helperText('Habilitar pagamentos via boleto bancário')
                            ->default(true),

                        Forms\Components\Toggle::make('pix_ativo')
                            ->label('PIX')
                            ->helperText('Habilitar pagamentos via PIX')
                            ->default(true),

                        Forms\Components\TextInput::make('desconto_pix')
                            ->label('Desconto PIX (%)')
                            ->numeric()
                            ->suffix('%')
                            ->default(5)
                            ->helperText('Percentual de desconto para pagamentos via PIX'),

                        Forms\Components\TextInput::make('juros_boleto')
                            ->label('Juros Boleto (%)')
                            ->numeric()
                            ->suffix('%')
                            ->default(2)
                            ->helperText('Percentual de juros para pagamentos via boleto'),
                    ]),

                Forms\Components\Section::make('Dados Bancários')
                    ->description('Informações da conta para recebimento')
                    ->schema([
                        Forms\Components\TextInput::make('banco')
                            ->label('Banco')
                            ->default('001 - Banco do Brasil')
                            ->disabled(),

                        Forms\Components\TextInput::make('agencia')
                            ->label('Agência')
                            ->default('2307-8')
                            ->disabled(),

                        Forms\Components\TextInput::make('conta')
                            ->label('Conta Corrente')
                            ->default('198.838-7')
                            ->disabled(),

                        Forms\Components\TextInput::make('favorecido')
                            ->label('Favorecido')
                            ->default('Instituto Bem Viver')
                            ->disabled(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Salvar Configurações')
                ->action('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        // Salvar configurações no arquivo de configuração
        $configPath = config_path('efi-client.php');
        $configContent = "<?php\n\nreturn " . var_export($data, true) . ";\n";
        
        file_put_contents($configPath, $configContent);

        Notification::make()
            ->title('Configurações salvas com sucesso!')
            ->success()
            ->send();
    }

    protected function getConfigData(): array
    {
        $configPath = config_path('efi-client.php');
        
        if (file_exists($configPath)) {
            return include $configPath;
        }

        return [
            'sandbox' => true,
            'cartao_ativo' => true,
            'boleto_ativo' => true,
            'pix_ativo' => true,
            'desconto_pix' => 5,
            'juros_boleto' => 2,
            'banco' => '001 - Banco do Brasil',
            'agencia' => '2307-8',
            'conta' => '198.838-7',
            'favorecido' => 'Instituto Bem Viver',
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole(['clienteapi', 'admin', 'financeiro']);
    }
} 