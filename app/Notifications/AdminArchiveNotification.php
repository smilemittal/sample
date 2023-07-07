<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Form;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminArchiveNotification extends Notification
{
    use Queueable;

    protected $from_user;
    protected $form;
    // protected $support = false;

    public function __construct(User $from_user, $form)
    {

        $this->from_user = $from_user;
        $this->form = $form;
        // $this->support = $support;
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

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */



    public function toDatabase()
    {
        return [
            'from_user_id' => $this->from_user->id,
            'from_user_name' => $this->from_user->name,
            'form_name' => $this->form,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->id,
            'notifiable_id' => $notifiable->id,
            'read_at' => null,
            'data' => [
                'from_user_id' => $this->from_user->id,
                'from_user_name' => $this->from_user->name,
                'form_name' => $this->form,
            ],
        ];
    }
}
