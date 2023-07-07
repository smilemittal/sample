<?php

namespace App\Http\Resources;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'valid_from'    => $this->valid_from,
            'valid_to'      => $this->valid_to,
            'price'         => $this->price,
            'plan_type'     => ($this->interval==1) ? 'Monthly' : 'Yearly',
            'status'        => $this->status == 0 ? Plan::InActive : Plan::Active,
            'encryptId'     => encrypt_tech($this->id)
        ];
    }
}
