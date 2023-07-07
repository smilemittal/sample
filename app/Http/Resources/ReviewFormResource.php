<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if ($this->status == 1){
            $status = 'Active';
            $statusClass = 'bg-btnSubmitBg text-btnSubmitText rounded-full px-3 py-1';
        } else {
            $status = 'Inactive';
            $statusClass = 'bg-bgAmberTag text-amber-500 rounded-full px-3 py-1';
        }
        if ($this->xme_area == 0){
            $moduleType = 'Library';
            $moduleClass = 'bg-btnSubmitBg text-btnSubmitText rounded-full px-3 py-1';
        } else {
            $moduleType = 'Xme Area';
            $moduleClass = 'bg-bgAmberTag text-amber-500 rounded-full px-3 py-1';
        }
        $url = '';
        if (count($this->attachements)>0) {
            foreach ($this->attachements()->getModels() as $attachement) {
                $url = $attachement->path;
              }
        } else {
               $url = '/images/placeholder-16-9.jpeg';
        }


        return [
            'id'                   => $this->id,
            'review_date'          => $this->review_date,
            'display_title'        => $this->display_title,
            'type'                 => $moduleType,
            'status'               => $status,
            'typeform_id'          => $this->typeform_id,
            'created_at'           => $this->created_at,
            'image'                => $url,
            'statusClass'          => $statusClass,
            'moduleClass'          => $moduleClass,
            'reviewFormattedDate'  => \Carbon\Carbon::parse($this->review_date)->format('d/m/Y')

          ];
    }
}
