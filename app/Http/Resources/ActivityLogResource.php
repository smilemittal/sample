<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {



        $user = User::find($this->user_id);
        $activity_subject = $this->subject;
        if (!$user) {
            $action_user = 'Deleted User #' . $this->user_id;
        } else {
            $action_user = $user->name;
        }
        $action_model = json_decode($this->json, true);
        $action_model_name = '';
        if ($this->model_name == 'User') {
            $action_model_name = $action_model['name'];
        } elseif ($this->model_name == 'Company') {
            $action_model_name = $action_model['name'];
        } elseif ($this->model_name == 'Group') {
            $action_model_name = $action_model['name'];
        } elseif ($this->model_name == 'Subject') {
            $action_model_name = $action_model['subject'];
        } elseif ($this->model_name == 'Form') {
            $action_model_name = $action_model['display_title'];
        } elseif ($this->model_name == 'EmailTemplate') {
            $action_model_name = $action_model['subject'];
        } elseif ($this->model_name == 'Directory') {
            $action_model_name = !empty($action_model['directory_name']) ? !empty($action_model['directory_name']) : '';
        } elseif ($this->model_name == 'Invite') {
            $action_model_name = $action_model['email'];
        }

        $subject = str_replace(['{user}', '{model}'], [$action_user, $action_model_name], $activity_subject);
        return [
            'id'         => $this->id,
            'modelName'  => $this->model_name,
            'modelId'    => $this->model_id,
            'userNote'   => $this->user_note,
            'user_id'    => $this->user_id,
            'json'       => $this->json,
            'systemNote' => $this->system_note,
            'subject'    => $subject,
            'ipAddress'  => $this->ip_address,
            'createdAt'  => $this->created_at->diffForHumans()
        ];
    }
}
