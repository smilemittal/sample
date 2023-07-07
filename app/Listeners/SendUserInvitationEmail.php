<?php

namespace App\Listeners;

use App\Helpers\NotificationHelper;

class SendUserInvitationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //Notification::send([$event->invite], new UserInviteMailNotification($event->invite));
        NotificationHelper::sendUserInviteNotification($event->invite);
    }
}
