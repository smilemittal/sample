<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Form;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminAssignFormToCompanyNotification extends Notification
{

    use Queueable;
    protected $from_user, $form, $to_user;


    public function __construct($data)
    {

        $this->from_user = $data['loggedUser'];
        $this->form      = $data['form'];
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



    public function toDatabase($notifiable)
    {
        return [
            'from_user_id' => $this->from_user->id,
            'from_user_name' => $this->from_user->name,
            'company_name' => $notifiable->company->name,
            'form_name' => $this->form->display_title,
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
            'from_user_id' => $this->from_user->id,
            'from_user_name' => $this->from_user->name,
            'company_name' => $notifiable->company->name,
            'form_name' => $this->form->display_title,
        ];
    }
}
