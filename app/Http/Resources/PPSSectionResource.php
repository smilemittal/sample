<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PPSSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      return [
        'id'         => $this->id,
        'title' => $this->title,
        'sub_heading' => $this->sub_heading,
        'display_order' => $this->display_order,
        'is_optional' => $this->is_optional,
        'section_detail' => $this->section_detail ? new SubjectPPSDataCollection($this->section_detail) : null,
      ];
    }
}
