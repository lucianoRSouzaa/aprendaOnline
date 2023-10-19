<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReport extends Notification
{
    use Queueable;

    protected $titleCourse;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($report)
    {
        $this->titleCourse = $report->course->title;
        $this->user = $report->user->name;
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
            'data' => $this->user . ' denunciou o curso: ' . $this->titleCourse
        ];
    }
}
