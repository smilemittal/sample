<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\LearningPathForm;
use App\Models\LearningPathHistory;
use App\Models\UserLearningPath;

class LearningPathResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $moduleStatus = 'Open';
        if (!empty($this->company_archived_at) || !empty($this->archived_at)) {
            $moduleStatus = 'Archive';
        } elseif (!empty($this->read_only)) {
            $moduleStatus = 'readOnly';
        }
        if ($this->status == 1) {
            $status = 'Active';
            $statusClass = 'bg-btnSubmitBg text-btnSubmitText text-xs rounded-full px-3 py-1';
        } else {
            $status = 'Inactive';
            $statusClass = 'bg-bgAmberTag text-amber-500 text-xs rounded-full px-3 py-1';
        }
        $url = '';
        if ($this->attachements && count($this->attachements)>0) {
            $attachement = $this->attachements()->getModels()[0];
            $url = $attachement->path;
        } else {
            $url = '/images/placeholder-16-9.jpeg';
        }
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'countModule' => $this->forms_count,
            'countMember'  => $this->members_count,
            'typeform_id'  => $this->typeform_id,
            'display_title' => $this->display_title,
            'created_at'    =>  \Carbon\Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'encryptId'    => encrypt_tech($this->id),
            'status'       => $status,
            'statusClass'  => $statusClass,
            'image'        => $url,
            'moduleCompletedAt' => $this->learning_created_at,
            'learningformCompletedCounter'        => $this->counter,
            'counterClass' => 'bg-bgAmberTag text-amber-500 text-xs rounded-full px-2 py-1',
            'learning_group_id' => $this->learning_group_id,
            'encrypt_learning_group_id' => encrypt_tech($this->learning_group_id),
            'moduleStatus'              => $moduleStatus,
            'archived_at'        => $this->archived_at,
            'company_archived_at' => $this->company_archived_at,
            'isLearningTraining' => count($this->learningpath)> 0 ? true : false,
          ];
    }
}
