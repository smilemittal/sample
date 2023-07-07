<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ThankYouFinalSubmitNotification extends Notification
{
    use Queueable;

    protected $from_user, $to_user, $subject, $support = false;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $from_user, Subject $subject, $support)
    {
        $this->from_user = $from_user;
        $this->support = $support;
        $this->subject = $subject;
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
            'from_user_id'      => $this->from_user->id,
            'from_user_name'    => $this->from_user->name,
            'from_user_avatar'  => $this->from_user->avatar,
            'is_support'        => $this->support,
            'subject_name'      => $this->subject->subject,
        ];
    }



    public function toArray($notifiable)
    {
        return [
            'from_user_id'      => $this->from_user->id,
            'from_user_name'    => $this->from_user->name,
            'from_user_avatar'  => $this->from_user->avatar,
            'is_support'        => $this->support,
            'subject_name'      => $this->subject->subject,
        ];
    }
}
