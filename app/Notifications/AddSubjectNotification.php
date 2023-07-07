<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddSubjectNotification extends Notification
{
    use Queueable;

    protected $fromUser;

    // protected $to_user;



    protected $subject, $support = false;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->fromUser  = $data['loggedUser'];
        $this->support   = $data['isSupport'];
        $this->subject   = $data['subject'];
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
            'from_user_id' => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'subject_name' => $this->subject->subject,
            'is_support' => $this->support,
        ];
    }



    public function toArray($notifiable)
    {
        return [
            'from_user_id' => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'subject_name' => $this->subject->subject,
            'is_support' => $this->support,
        ];
    }
}
