<?php

namespace App\Jobs;

use Illuminate\Support\Facades\App;
use App\Mail\Email;
use App\Services\MailerService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReportaTransacaoViaEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected array $dados;
    public int $tries = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(array $dados)
    {
        $this->dados = $dados;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        App::setLocale('pt_BR');

        try {
            // Create an instance of Email using the create() method
            $email = Email::create(app(MailerService::class)); // Inject MailerService

            // Prepare envelope details
            $email->envelope([
                'to' => $this->dados['envelope']['to'],
                'cc' => $this->dados['envelope']['cc'] ?? null, // Include cc if exists
                'bcc' => $this->dados['envelope']['bcc'] ?? null, // Include bcc if exists
                'assunto' => $this->dados['envelope']['assunto'],
            ]);

            // Prepare template details
            $email->template([
                'view' => $this->dados['template']['view'],
                'dados' => $this->dados['template']['dados'],
            ]);

            // Send the email
            $email->enviar();

        } catch (Exception $e) {
            Log::error('Erro ao enviar o e-mail: ' . $e->getMessage());
        }
    }
}
