<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\UserForm;
use App\Models\Form;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAssignFormNotification extends Notification
{
    use Queueable;
    protected $fromUser, $support;
    protected $user;
    protected $form;
    // protected $support = false;

    public function __construct(array $data)
    {
        // dd($form);
        $this->fromUser  = $data['loggedUser'];
        $this->support   = $data['isSupport'];
        $this->form      = $data['moduleData'];
        $this->user      = $data['member'];
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
            'from_user_id'      => $this->fromUser->id,
            'from_user_name'    => $this->fromUser->name,
            'form_name'         => $this->form,
            'user_name'         => $this->user[0]->name,
            'is_support'        => $this->support,
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
            'from_user_id'      => $this->fromUser->id,
            'from_user_name'    => $this->fromUser->name,
            'form_name'         => $this->form,
            'user_name'         => $this->user[0]->name,
            'is_support'        => $this->support,
        ];
    }
}
