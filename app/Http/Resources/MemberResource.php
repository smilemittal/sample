<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Company;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $archiveMenuVisible = true;
        $this->avatar ? ($avatarurl = '/'.$this->avatar) : ($avatarurl = '/images/placeholder-profile.png');
        $company = Company::withTrashed()->find($this->company_id);
        $role = $this->role->name;
        if ($role == 'company' && isCompanyAdmin()){
            $archiveMenuVisible = false;
        }
        if ($role == 'businessadmin' && isBusinessAdmin() ) {
            $archiveMenuVisible = false;
        }

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'role_id'       => $this->role_id,
            'avatar'        => $avatarurl,
            'email'         => $this->email,
            'company'       =>  $company->name,
            'url'           => $avatarurl,
            'role'          =>  ($this->role) ? $this->role->display_name : '',
            'roleName'      =>  ($this->role) ? $this->role->name : '',
            'encryptId'     => encrypt_tech($this->id),
            'scheduled_at'  => $this->scheduled_at,
            'archived_at'   => $this->archived_at,
            'archiveMenuVisible' => $archiveMenuVisible
          ];
    }
}
