<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AddedGroupNotification extends Notification
{
    use Queueable;

    protected $fromUser, $group, $support = false;

    public function __construct(array $data)
    {
        $this->fromUser   = $data['loggedUser'];
        $this->group      = $data['group'];
        $this->support    = $data['isSupport'];
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
            'from_user_id'     => $this->fromUser->id,
            'from_user_name'   => $this->fromUser->name,
            'group_name'       => $this->group->name,
            'company_name'     => $this->group->company->name,
            'is_support'       => $this->support,
            'group_id'         => $this->group->id,
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable):array
    {
        return [
            'from_user_id'     => $this->fromUser->id,
            'from_user_name'   => $this->fromUser->name,
            'group_name'       => $this->group->name,
            'company_name'     => $this->group->company->name,
            'is_support'       => $this->support,
            'group_id'         => $this->group->id,
        ];
    }
}
