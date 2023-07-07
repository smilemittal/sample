<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberPendingInviteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id'         => $this->id,
            'email'      => $this->email,
            'company'    =>  $this->company->name ?? 'ERR',
            'created_at' =>  $this->created_at->diffForHumans()
          ];
    }
}
