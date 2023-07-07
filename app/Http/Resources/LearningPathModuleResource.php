<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\LearningPathForm;
use App\Models\LearningPathHistory;
use App\Models\UserLearningPath;

class LearningPathModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        $url = '';
        if (count($this->form->attachements)>0) {
            $attachement = $this->form->attachements()->getModels()[0];
            $url = $attachement->path;
        } else {
            $url = '/images/placeholder-16-9.jpeg';
        }
            return [
            'id'            => $this->id,
            'display_title' => $this->display_title,
            'created_at'    => \Carbon\Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'image'         =>  $url,
            'formId'        => $this->form->id,
            'readOnly'      => $this->read_only,
            'archived_at'    => $this->archived_at,
            'company_archived_at' => $this->company_archived_at
          ];
    }
}
