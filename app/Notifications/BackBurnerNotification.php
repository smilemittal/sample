<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BackBurnerNotification extends Notification
{
    use Queueable;


    protected $from_user, $subject, $support = false;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $from_user, Subject $subject, $support = false)
    {
        $this->from_user = $from_user;
        $this->subject   = $subject;
        $this->support   = $support;
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
            'from_user_id'   => $this->from_user->id,
            'from_user_name' => $this->from_user->name,
            'subject_name'   => $this->subject->subject,
            'is_support'     => $this->support,
        ];
    }



    public function toArray($notifiable)
    {
        return [
            'from_user_id'   => $this->from_user->id,
            'from_user_name' => $this->from_user->name,
            'subject_name'   => $this->subject->subject,
            'is_support'     => $this->support,
        ];
    }
}
