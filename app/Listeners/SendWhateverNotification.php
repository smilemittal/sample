<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SendWhateverNotification
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        ddd("handle whatever");

        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            // $event->user->SendWhateverNotification();
        }
    }
}
