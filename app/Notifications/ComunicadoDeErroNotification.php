<?php

namespace App\Notifications;

use App\Models\ComunicadoDeErro;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComunicadoDeErroNotification extends Notification
{
    use Queueable;

    protected $comunicadoDeErro;

    /**
     * Create a new notification instance.
     */
    public function __construct(ComunicadoDeErro $comunicadoDeErro)
    {
        $this->comunicadoDeErro = $comunicadoDeErro;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // $falecido = $this->comunicadoDeErro->falecido;

        $tipoErroLabel = ComunicadoDeErro::TIPOS_ERRO_LABEL[$this->comunicadoDeErro->tipo_erro] ?? $this->comunicadoDeErro->tipo_erro;
        
        return (new MailMessage)
                    ->subject('CNF - Novo Comunicado de Erro Recebido')
                    ->greeting('Olá!')
                    ->line('Um novo comunicado de erro foi recebido no sistema CNF.')
                    ->line('**Detalhes do Comunicado:**')
                    ->line('**Nome do Comunicante:** ' . $this->comunicadoDeErro->nome_comunicante)
                    ->line('**Email do Comunicante:** ' . $this->comunicadoDeErro->email_comunicante)
                    ->line('**Falecido Relacionado:** ' . $this->comunicadoDeErro->nome_falecido)
                    ->line('**Cidade:** ' . $this->comunicadoDeErro->cidade_falecido . '/' . $this->comunicadoDeErro->uf_falecido)
                    ->line('**Tipo de Erro:** '  . $tipoErroLabel)
                    ->line('**Mensagem:** ' . $this->comunicadoDeErro->mensagem)
                    ->line('**Data do Comunicado:** ' . $this->comunicadoDeErro->created_at->format('d/m/Y H:i:s'))
                    ->action('Visualizar no Painel', url('/dashboard/comunicado-de-erros/' . $this->comunicadoDeErro->id . '/edit'))
                    ->line('Por favor, analise este comunicado e tome as medidas necessárias.')
                    ->salutation('Atenciosamente, Sistema CNF');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'comunicado_id' => $this->comunicadoDeErro->id,
            'nome_comunicante' => $this->comunicadoDeErro->nome_comunicante,
            'email_comunicante' => $this->comunicadoDeErro->email_comunicante,
            'mensagem' => $this->comunicadoDeErro->mensagem,
        ];
    }
} 