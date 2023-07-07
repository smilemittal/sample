<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyUpdateResource extends JsonResource
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
              $attachement = $this->attachements()->getModels()[0];
              $url = $attachement->path;
              
        } else {
               $url = 'images/placeholder-16-9.jpeg';
        }


        return [
            'id'                   => $this->id,
            'review_date'          => $this->review_date,
            'display_title'        => $this->display_title,
            'type'                 => $moduleType,
            'status'               => $status,
            'typeform_id'          => $this->typeform_id,
            'image'                => $url,
            'statusClass'          => $statusClass,
            'moduleClass'          => $moduleClass,
            'created_at'           => \Carbon\Carbon::parse($this->created_at)->format('Y:m:d H:i:s')

          ];
    }
}
