<?php

namespace App\Providers;

use App\Providers\UserAcceptedInvitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserAcceptedInvitationNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserAcceptedInvitation $event): void
    {
        //
    }
}
