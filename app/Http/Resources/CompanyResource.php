<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $logourl = $this->logo ? '/'.$this->logo : '/images/placeholder-logo.jpeg';
        return [
            'id'         => $this->id,
            'name'    => $this->name,
            'phone_nr'     => $this->phone_nr,
            'logo'     => $logourl,
            'status'   => $this->status,
            'abn' =>  $this->abn,
            'street' => $this->street,
            'city' => $this->city,
            'state'       => $this->state,
            'postcode' => $this->postcode,
            'website'     =>  $this->website,
            'years_trading'      => $this->years_trading,
            'created_at'      => $this->created_at->formatLocalized('%d/%m/%Y'),
            'archived_at'     => $this->archived_at,
            'encryptId'     => encrypt_tech($this->id)
          ];
    }
}
