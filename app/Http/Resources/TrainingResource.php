<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Group;

class TrainingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $url =  $time = $group = '';
        if ($this->form->xme_area == 1) {

            $group = 'Xme';
        } else {
            if ($this->group_id == -1) {
                $group = 'Library';

            } elseif ($this->group_id == 0) {
                $group ='Direct Assign ';
            } else {
                $group = ($this->group ==null) ? 'Group' : $this->group->name;
            }
        }
        if (count($this->form->attachements)>0) {
            $attachement = $this->form->attachements()->getModels()[0];
            $url = $attachement->path;
        } else {
            $url = '/images/placeholder-16-9.jpeg';
        }
        $groupType = ($this->direct > 0) ? Group::getGroup($this->direct) : 'Direct';
        if ($this->status == 1) {
            $status = 'Active';
            $statusClass = 'bg-btnSubmitBg text-btnSubmitText rounded-full px-3 py-1';
        } elseif ($this->status == 'completed') {
            $status = 'Completed';
            $statusClass = 'bg-btnSubmitBg text-btnSubmitText rounded-full px-3 py-1';
            $time = $this->completed_date;
        } elseif ($this->status == 'pending') {
            $status = 'Pending';
            $statusClass = 'bg-btnSubmitBg text-btnSubmitText rounded-full px-3 py-1';
            $time = $this->expiry_date;
        } elseif ($this->status == 'skipped') {
            $status = 'Skipped';
            $time = $this->expiry_date;
            $statusClass = 'bg-btnSubmitBg text-btnSubmitText rounded-full px-3 py-1';
        } else {
            $status = 'Inactive';
            $statusClass = 'bg-bgAmberTag text-amber-500 rounded-full px-3 py-1';
        }



        return [
            'id'      => $this->id,
            'encryptFormId' => encrypt_tech($this->id),
            'company_id' => $this->company_id,
            'typeform_id' => $this->typeform_id,
            'image'   => $url,
            'groupType' => $groupType,
            'created_at'  => \Carbon\Carbon::parse($this->created_at)->format('Y:m:d H:i:s'),
            'display_title' => $this->display_title,
            'status'        => $status,
            'statusClass'  => $statusClass,
            'assigned_date' => $this->assigned_date,
            'completedDate' =>  $time,
            'TrainingId'    => encrypt_tech($this->training_id),
            'group'         => $group,

          ];
    }
}
