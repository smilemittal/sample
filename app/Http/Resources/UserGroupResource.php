<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Company;

class UserGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        $this->user->avatar ? ($avatarurl = '/'.$this->user->avatar) : ($avatarurl = '/images/placeholder-profile.png');
        $company = Company::withTrashed()->find($this->user->company_id);

        return [
            'id'            => $this->id,
            'encryptId'     => encrypt_tech($this->id),
            'user_id'       => $this->user_id,
            'scheduled_at'  => $this->scheduled_at,
            'archived_at'   => $this->archived_at,
            'name'          => $this->user->name,
            'role_id'       => $this->user->role_id,
            'avatar'        => $avatarurl,
            'email'         => $this->user->email,
            'company'       =>  $company->name,
            'url'           => $avatarurl,
            'user_archived_at' => $this->user->archived_at,
            'role'          =>  ($this->role) ? $this->role->display_name : '',
            'roleName'      =>  ($this->role) ? $this->role->name : '',
          ];
    }
}
