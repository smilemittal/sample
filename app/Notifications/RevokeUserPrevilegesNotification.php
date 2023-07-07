<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RevokeUserPrevilegesNotification extends Notification
{
    use Queueable;

    protected $fromUser, $user, $support = false;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->fromUser  = $data['loggedUser'];
        $this->support   = $data['isSupport'];
        $this->user      = $data['user'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }


    public function toDatabase()
    {
        return [
            'from_user_id'   => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'user_name'      => $this->user->name,
            'is_support'     => $this->support,
        ];
    }



    public function toArray($notifiable)
    {
        return [
            'from_user_id'   => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'user_name'      => $this->user->name,
            'is_support'     => $this->support,
        ];
    }
}
