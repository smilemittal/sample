<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeleteDirectoryNotification extends Notification
{
    use Queueable;
    protected $fromUser, $directory, $support = false;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->fromUser     = $data['loggedUser'];
        $this->directory    = $data['directory'];
        $this->support      = $data['isSupport'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase()
    {

        return [
            'from_user_id'        => $this->fromUser->id,
            'from_user_name'      => $this->fromUser->name,
            'directory_name'      => $this->directory->name,
            'is_support'          => $this->support,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'from_user_id'        => $this->fromUser->id,
            'from_user_name'      => $this->fromUser->name,
            'directory_name'      => $this->directory->name,
            'is_support'          => $this->support,
        ];
    }
}
