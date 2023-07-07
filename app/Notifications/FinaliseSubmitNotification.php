<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinaliseSubmitNotification extends Notification
{
    use Queueable;
    protected $fromUser, $subject, $support = false;
    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {

        $this->fromUser =  $data['loggedUser'];
        $this->support =  $data['isSupport'];
        $this->subject =  $data['subject'];
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
            'from_user_id'          => $this->fromUser->id,
            'from_user_name'        => $this->fromUser->name,
            'from_user_avatar'      => $this->fromUser->avatar,
            'is_support'            => $this->support,
            'subject_name'          => $this->subject->subject,
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
            'from_user_id'          => $this->fromUser->id,
            'from_user_name'        => $this->fromUser->name,
            'from_user_avatar'      => $this->fromUser->avatar,
            'is_support'            => $this->support,
            'subject_name'          => $this->subject->subject,
        ];
    }
}
