<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Str;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The notification's unique identifier.
     *
     * @var string
     */
    public $id;

    /**
     * The notification's title.
     *
     * @var string
     */
    protected $title;

    /**
     * The notification's message.
     *
     * @var string
     */
    protected $message;

    /**
     * The notification's data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->id = (string) Str::uuid();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject($this->title)
            ->line($this->message)
            ->action('Ver Notificação', url('/notifications'));

        // Store email content in the data array
        $this->data['email'] = [
            'subject' => $this->title,
            'message' => $this->message,
            'action_text' => 'Ver Notificação',
            'action_url' => url('/notifications'),
            'sent_at' => now(),
        ];

        return $mailMessage;
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data,
            'type' => 'email', // Add type to distinguish email notifications
        ]);
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    /**
     * Set the notification title.
     */
    protected function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set the notification message.
     */
    protected function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set the notification data.
     */
    protected function setData(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
} 