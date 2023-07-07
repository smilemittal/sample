<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'subject'       => $this->subject,
            'content'       => $this->content,
            'enable'        => $this->enable,
            'display_order' => $this->display_order,
            'encryptId'     => encrypt_tech($this->id)
        ];
    }
}
