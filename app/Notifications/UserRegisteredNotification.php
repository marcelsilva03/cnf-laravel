<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserRegisteredNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected User $user
    ) {
        parent::__construct();
        
        $this->setTitle('Novo Usuário Registrado')
            ->setMessage("O usuário {$this->user->name} foi registrado com sucesso.")
            ->setData([
                'user_id' => $this->user->id,
                'user_name' => $this->user->name,
                'user_email' => $this->user->email,
                'registered_at' => $this->user->created_at,
            ]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }
} 