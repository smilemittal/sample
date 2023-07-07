<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class GroupMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avatarurl = '/';
        $this->avatar ? ($avatarurl .= $this->avatar) : ($avatarurl = '/images/placeholder-profile.png');
            return [
            'id'         => $this->id,
            'image'  => $avatarurl,
            'name'       => $this->name,
            'email'      => $this->email,
          ];
    }
}
