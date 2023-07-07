<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserAddedToGroupNotification extends Notification
{
    use Queueable;

    protected $from_user;

    protected $to_user;

    protected $group;

    protected $support = false;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $from_user, User $to_user, Group $group, $support=false)
    {
        $this->from_user = $from_user;
        $this->to_user = $to_user;
        $this->group = $group;
        $this->support = $support;
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
            'from_user_avatar' => $this->from_user->avatar,
            'to_user_id' => $this->to_user->id,
            'to_user_name' => $this->to_user->name,
            'to_user_avatar' => $this->to_user->avatar,
            'group_name' => $this->group->name,
            'group_id'=>$this->group->id,
            'is_support' => $this->support,
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
                'from_user_avatar' => $this->from_user->avatar,
                'to_user_id' => $this->to_user->id,
                'to_user_name' => $this->to_user->name,
                'to_user_avatar' => $this->to_user->avatar,
                'group_name' => $this->group->name,
                'group_id'=>$this->group->id,
                'is_support' => $this->support,
            ],
        ];
    }
}