<?php

namespace App\Mail;

use Exception;
use App\Services\MailerService;

class Email
{
    protected MailerService $mailer;
    protected string $assunto;
    protected array $template = ['view' => '', 'dados' => []];
    protected array $to = [];
    protected array $cc = [];
    protected array $bcc = [];
    protected array $headers = [];

    public function __construct(MailerService $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws Exception
     */
    public static function create(MailerService $mailer): Email
    {
        return new self($mailer);
    }

    /**
     * @throws Exception
     */
    public function envelope(array $envelope): static
    {
        if (!$envelope['to']) {
            throw new Exception('O argumento envelope deve conter a chave "to" sendo string ou array.');
        }
        if (!$envelope['assunto']) {
            throw new Exception('O argumento envelope deve conter a chave "assunto" sendo string.');
        }
        $this->to = $this->preparaEndereco($envelope['to']);
        if (isset($envelope['cc'])) {
            $this->cc = $this->preparaEndereco($envelope['cc']);
        }
        if (isset($envelope['bcc'])) {
            $this->bcc = $this->preparaEndereco($envelope['bcc']);
        }
        $this->assunto = $envelope['assunto'];

        return $this;
    }

    /**
     * @throws Exception
     */
    public function template(array $template): static
    {
        if (!$this->templateValido($template)) {
            throw new Exception('O argumento template deve conter as chaves (string)"view" e (array)"dados"');
        }
        $this->template = $template;
        return $this;
    }

    public function withHeaders(array $headers): static
    {
        $this->headers = $headers;
        return $this;
    }

    private function templateValido(array $template): bool
    {
        return $template['view'] && $template['dados'];
    }

    private function preparaEndereco(array|string $endereco): array
    {
        return is_string($endereco) ? [$endereco] : $endereco;
    }

    private function preparaCorpo(): string
    {
        return view($this->template['view'])
            ->with($this->template['dados'])
            ->render();
    }

    /**
     * @throws Exception
     */
    public function enviar(): bool
    {
        if (
            empty($this->to)
            || empty($this->assunto)
            || empty($this->template['view'])
            || empty($this->template['dados'])
        ) {
            throw new Exception('Os parâmetros "to", "assunto", "view" e "dados" são obrigatórios para o envio do email.');
        }
        $corpo = $this->preparaCorpo();
        $this->mailer->prepare($this->assunto, $corpo);
        $this->mailer->addCarbonCopies($this->cc, $this->bcc);
        $this->mailer->setHeaders($this->headers);
        return $this->mailer->sendMail($this->to);
    }
}
