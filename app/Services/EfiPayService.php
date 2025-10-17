<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EfiPayService
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $p12Path;
    protected string $p12Password;
    protected string $webhookUrl;

    public function __construct()
    {
        $env = strtolower(env('EFI_ENV', 'sandbox'));

        // URL base da API Pix conforme ambiente
        $this->baseUrl = in_array($env, ['prod', 'production'])
            ? 'https://pix.api.efipay.com.br'
            : 'https://pix-h.api.efipay.com.br';

        // Credenciais conforme ambiente
        $this->clientId     = $env === 'prod'
            ? env('EFI_CLIENT_ID_PROD')
            : env('EFI_CLIENT_ID_HOMOL');
        $this->clientSecret = $env === 'prod'
            ? env('EFI_CLIENT_SECRET_PROD')
            : env('EFI_CLIENT_SECRET_HOMOL');

        // Resolve caminho do certificado P12 (absoluto ou relativo)
        $certPath = env('EFI_CERTIFICATE_PATH');
        if (Str::startsWith($certPath, ['/', 'C:', 'D:'])) {
            $this->p12Path = $certPath;
        } elseif (Str::startsWith($certPath, 'certs/')) {
            $this->p12Path = storage_path($certPath);
        } elseif (! Str::contains($certPath, '/')) {
            $this->p12Path = storage_path("certs/{$certPath}");
        } else {
            $this->p12Path = base_path($certPath);
        }
        if (! file_exists($this->p12Path)) {
            throw new \InvalidArgumentException("Certificado P12 não encontrado: {$this->p12Path}");
        }

        $this->p12Password = env('EFI_PWD_CERTIFICATE', '');

        // URL do webhook (base) definida no .env
        $this->webhookUrl = env('EFI_WEBHOOK_URL');
        if (empty($this->webhookUrl)) {
            throw new \InvalidArgumentException('A variável EFI_WEBHOOK_URL não está definida no .env');
        }
    }

    /**
     * Obtém o OAuth2 access_token da EfíPay.
     */
    public function getAccessToken(): string
    {
        $response = Http::withOptions([
                'cert'      => [$this->p12Path, $this->p12Password],
                'cert_type' => 'P12',
            ])
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post("{$this->baseUrl}/oauth/token", [
                'grant_type' => 'client_credentials',
            ]);

        $response->throw();
        return $response->json('access_token');
    }

    /**
     * Registra o webhook Pix para a chave informada.
     */
    public function registerWebhook(string $pixKey): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->withOptions([
                'cert'      => [$this->p12Path, $this->p12Password],
                'cert_type' => 'P12',
            ])
            // Não utiliza skip-mTLS: mTLS obrigatório para registro
            ->put(
                "{$this->baseUrl}/v2/webhook/{$pixKey}",
                [
                    'webhookUrl' => $this->webhookUrl . '?ignorar=',
                ]
            );

        $response->throw();
        return $response->json();
    }
}