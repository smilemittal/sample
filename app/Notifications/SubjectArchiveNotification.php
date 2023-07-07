<?php

namespace App\Notifications;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubjectArchiveNotification extends Notification
{
    use Queueable;


    protected $from_user, $archiveReason, $subject, $support = false, $actionType, $actionPage;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {

        $this->from_user    =  $data['loggedUser'];
        $this->support      =  $data['isSupport'];
        $this->subject      =  $data['subject'];
        $this->archiveReason =  $data['reason'];
        $this->actionType   =  $data['actionType'];
        $this->actionPage   =  $data['actionPage'];
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
    public function toDatabase()
    {
        return [
            'from_user_id'          => $this->from_user->id,
            'from_user_name'        => $this->from_user->name,
            'from_user_avatar'      => $this->from_user->avatar,
            'is_support'            => $this->support,
            'subject_name'          => $this->subject->subject,
            'actionType'            => $this->actionType,
            'archiveReason'         => $this->archiveReason,
            'actionPage'            => $this->actionPage
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
            'from_user_id'          => $this->from_user->id,
            'from_user_name'        => $this->from_user->name,
            'from_user_avatar'      => $this->from_user->avatar,
            'is_support'            => $this->support,
            'subject_name'          => $this->subject->subject,
            'actionType'            => $this->actionType,
            'archiveReason'         => $this->archiveReason,
            'actionPage'            => $this->actionPage
        ];
    }
}
