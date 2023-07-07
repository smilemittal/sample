<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\ActionLog;

class XmeActionLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user; // object for logged in user

    public $model; // object that was acted on (optional)

    public $subject; // subject of the action

    public $actionName; // name of the action performed

    public $support;


    /**
     * Create a new job instance.
     *
     * @param string $actionName Name of the action performed
     * @param string $subject Subject of the action
     * @param object|null $model Object that was acted on (optional)
     */
    public function __construct(object $user, string $actionName, string $subject, ?object $model)
    {
        $this->user = $user;
        $this->model = $model;
        $this->subject = $subject;
        $this->actionName = $actionName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $actionLog = new ActionLog;
        $actionLog->model_name = !empty($this->model) ? class_basename($this->model) : null;
        $actionLog->model_id = !empty($this->model) ? $this->model->id : null;
        $actionLog->user_id = $this->user->id;
        $actionLog->json = !empty($this->model) && is_object($this->model) ?
            json_encode($this->model->getAttributes()) : null;
        $actionLog->system_note = $this->actionName;
        $actionLog->subject = $this->subject;
        $actionLog->ip_address = Request::getClientIp();
        $actionLog->browser = Request::header('User-Agent');
        $actionLog->is_support = 0;
        $actionLog->save();
    }
}
