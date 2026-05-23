<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MobilePushNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $actionedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $actionedBy = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->actionedBy = $actionedBy;
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
            'title' => $this->title,
            'message' => $this->message,
            'actioned_by' => $this->actionedBy,
        ];
    }
}
