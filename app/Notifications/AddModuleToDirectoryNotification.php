<?php

namespace App\Notifications;

use App\Models\Company;
use App\Models\DirectoryModule;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AddModuleToDirectoryNotification extends Notification
{
    use Queueable;

    protected $fromUser, $company, $directoryModule, $support = false;
    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->fromUser           = $data['loggedUser'];
        $this->directoryModule    = $data['directoryModule'];
        $this->company            = $data['company'];
        $this->support            = $data['isSupport'];
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
            'from_user_id'   => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'directory_name' => $this->directoryModule->directory->name,
            'module_name'    => $this->directoryModule->directoryModules->display_title,
            'company_name'   => $this->company->name,
            'is_support'     => $this->support,
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
            'from_user_id'   => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'directory_name' => $this->directoryModule->directory->name,
            'module_name'    => $this->directoryModule->directoryModules->display_title,
            'company_name'   => $this->company->name,
            'is_support'     => $this->support,
        ];
    }
}
