<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class IdeaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $data;
    private $notificationType;

    public function __construct($data, $notificationType)
    {
        $this->data = $data;
        $this->notificationType = $notificationType;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
           'user_id' => $notifiable->id,
           'data' => array_merge($this->data, [
               'notification_type' => $this->notificationType
           ]),
        ];
    }
}
