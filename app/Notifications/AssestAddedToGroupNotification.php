<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\User;
use App\Models\Form;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssestAddedToGroupNotification extends Notification
{
    use Queueable;
    protected $fromUser, $group, $form, $support = false;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        //$this->to_user = $to_user;
        $this->fromUser         = $data['loggedUser'];
        $this->group            = $data['group'];
        $this->support          = $data['isSupport'];
        $this->form             = $data['form'];
        $this->form->form_id    = $data['form']->id;
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
            'from_user_id'     => $this->fromUser->id,
            'from_user_name'   => $this->fromUser->name,
            'from_user_avatar' => $this->fromUser->avatar,
            'group_name'       => $this->group->name,
            'group_id'         => $this->group->id,
            'is_support'       => $this->support,
            'form_name'        => $this->form->display_title,
            'form_id'          => $this->form->id,
        ];
    }


    public function toArray($notifiable)
    {
        return [
            'from_user_id'     => $this->fromUser->id,
            'from_user_name'   => $this->fromUser->name,
            'from_user_avatar' => $this->fromUser->avatar,
            'group_name'       => $this->group->name,
            'group_id'         => $this->group->id,
            'is_support'       => $this->support,
            'form_name'        => $this->form->display_title,
            'form_id'          => $this->form->id,
        ];
    }
}
