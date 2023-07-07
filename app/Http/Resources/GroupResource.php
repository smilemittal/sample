<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class GroupResource extends JsonResource
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
            'name'       => $this->name,
            'company_id' => $this->company_id,
            'members'    => $this->members_count,
            'forms'      => $this->forms_count,
            'companyName' => $this->company->name ?? 'ERR',
            'encryptId'     => encrypt_tech($this->id),
            'is_assigned_default' => ($this->is_assigned_default ),
            'default_assignned_roles' =>   explode(',', $this->default_assignned_roles),
            'created_at'   => $this->created_at->diffForHumans()
          ];
    }
}
