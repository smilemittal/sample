<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\NotificationHelper;
use Illuminate\Database\Eloquent\Collection;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $notificationName; // name of the notification class
    private $users; // array of users to notify
    private $data; // additional data to pass to the notification
    private $mailMethod; // method name for sending email notifications
    private $emailUserList; // array of users to receive email notifications
    
    /**
     * Create a new job instance.
     *
     * @param Collection $users  Collection of users to notify
     * @param array $data  additional data to pass to the notification
     * @param string|null $notificationName  name of the notification class
     * @param string|null $mailMethod  method name for sending email notifications
     * @param Collection|null $emailUserList  Collection of users to receive email notifications, defaults to $users if null
     */
    public function __construct($users, array $data = [], ?string $notificationName, ?string $mailMethod, ?Collection $emailUserList)
    {
        $this->notificationName = $notificationName;
        $this->data = $data;
        $this->users = $users;
        $this->mailMethod = $mailMethod;
        $this->emailUserList = $emailUserList ?? $users; // if $emailUserList is null, use $users instead
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        // Notify each user using the specified notification class and passing any additional data
        if ($this->notificationName) {
            if (count($this->users)>0) {
                foreach ($this->users as $user) {
                    $class = '\App\Notifications\\'. $this->notificationName;
                    $user->notify(new $class($this->data));
                }
            }
        }

        // Call the specified email notification method from NotificationHelper, passing the array of users and data
        if ($this->mailMethod && count($this->emailUserList)>0) {
            call_user_func_array(
                [NotificationHelper::class, $this->mailMethod],
                ['users' => $this->emailUserList, 'data' => $this->data]
            );
        }
    }
}
