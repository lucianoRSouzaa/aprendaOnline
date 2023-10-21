<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FirstReportNotification extends Notification
{
    use Queueable;

    protected $denuncia;

    /**
     * Create a new notification instance.
     */
    public function __construct($denuncia)
    {
        $this->denuncia = $denuncia;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'data' => 'Denúncia aceita no seu curso "' .  $this->denuncia . '" Evite o banimento!'
        ];
    }
}
