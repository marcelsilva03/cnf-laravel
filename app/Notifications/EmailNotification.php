<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EmailNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected EmailTemplate $template,
        protected $data = []
    ) {
        parent::__construct();
        
        $this->setTitle($template->parseSubject($data))
            ->setMessage($template->parseContent($data))
            ->setData([
                'email' => [
                    'subject' => $template->parseSubject($data),
                    'content' => $template->parseContent($data),
                    'action_text' => $template->action_text,
                    'action_url' => $template->parseActionUrl($data),
                    'sent_at' => now(),
                    'template_id' => $template->id,
                    'template_name' => $template->name,
                ],
                ...$data
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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        // Log email request details
        Log::info('Email notification request', [
            'template_id' => $this->template->id,
            'template_name' => $this->template->name,
            'recipient' => $notifiable->email,
            'subject' => $this->template->parseSubject($this->data),
            'data' => $this->data,
            'timestamp' => now()->toDateTimeString(),
        ]);

        $mailMessage = (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($this->template->parseSubject($this->data))
            ->line($this->template->parseContent($this->data));

        if ($this->template->action_text && $this->template->action_url) {
            $mailMessage->action(
                $this->template->action_text,
                $this->template->parseActionUrl($this->data)
            );
        }

        // Add attachments
        foreach ($this->template->attachments as $attachment) {
            $mailMessage->attach(
                Storage::path($attachment->path),
                ['as' => $attachment->name, 'mime' => $attachment->mime_type]
            );
        }

        // Track email sent
        $this->template->trackEvent('sent', $notifiable, $this->data);

        return $mailMessage;
    }

    /**
     * Handle a successful delivery of the notification.
     */
    public function delivered(object $notifiable): void
    {
        // Track email opened
        $this->template->trackEvent('opened', $notifiable, $this->data);
    }

    /**
     * Handle a click on the action button.
     */
    public function actionClicked(object $notifiable): void
    {
        // Track button click
        $this->template->trackEvent('clicked', $notifiable, $this->data);
    }
} 