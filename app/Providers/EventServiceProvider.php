<?php

namespace App\Providers;

use App\Events\UserAcceptedInvitation;
use App\Events\UserInvited;
use App\Listeners\SendUserAcceptedInvitationNotification;
use App\Listeners\SendUserInvitationEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
   
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserInvited::class => [
            SendUserInvitationEmail::class,

        ],
        UserAcceptedInvitation::class => [
            SendUserAcceptedInvitationNotification::class,
        ],
        TypeFormWebhookReceived::class => [
            TypeFormEventListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
