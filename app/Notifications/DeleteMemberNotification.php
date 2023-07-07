<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Group;
use App\Models\UserGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeleteMemberNotification extends Notification
{
    use Queueable;

    protected $from_user;
    protected $support = false;
    protected $group ;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $from_user,Group $group,UserGroup $member,$support=false)
    {
        $this->from_user = $from_user;
        $this->group = $group;
        $this->member = $member->user->name;
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
            'group_name' => $this->group->name,
            'group_id'=>$this->group->id,
            'member_name'=> $this->member,
            'from_user_avatar' => $this->from_user->avatar,
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
                'group_name' => $this->group->name,
                'group_id'=>$this->group->id,
                'member_name'=> $this->member,
                'from_user_avatar' => $this->from_user->avatar,
                'is_support' => $this->support,
            ],
        ];
    }
}
