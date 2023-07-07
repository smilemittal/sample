<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\NewUserMailNotification;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNewUserNotification
{

     /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $admins = User::where('role_id', '1')->get();

        Notification::send($admins, new NewUserNotification($event->user));
        
        Notification::send($admins, new NewUserMailNotification($event->user));

    }
}
