<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Form;

class FormArchiveNotification extends Notification
{
    use Queueable;
    protected $formUser;
    protected $form;
    protected $support = false;
    protected $actionType;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $formUser, Form $form, $support, $actionType)
    {

        $this->formUser = $formUser;
        $this->support = $support;
        $this->form = $form;
        $this->actionType = $actionType;
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
    public function toMail(object $notifiable)
    {
        return [
            'from_user_id'          => $this->formUser->id,
            'from_user_name'        => $this->formUser->name,
            'from_user_avatar'      => $this->formUser->avatar,
            'is_support'            => $this->support,
            'form_name'             => $this->form->display_title,
            'actionType'           => $this->actionType
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
            'id'                => $this->id,
            'notifiable_id'     => $notifiable->id,
            'read_at'           => null,
            'data' => [
                'from_user_id'   => $this->formUser->id,
                'from_user_name' => $this->formUser->name,
                'is_support'     => $this->support,
                'form_name'      => $this->form->display_title,
                'actionType'     => $this->actionType
            ],
        ];
    }

    public function toDatabase()
    {
        return [
            'from_user_id'      => $this->formUser->id,
            'from_user_name'    => $this->formUser->name,
            'from_user_avatar'  => $this->formUser->avatar,
            'is_support'        => $this->support,
            'form_name'         => $this->form->display_title,
            'form_id'           => $this->form->id,
            'actionType'     => $this->actionType
        ];
    }

}
