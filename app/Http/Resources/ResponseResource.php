<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
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
          'submittedAt' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->submitted_at)
          ->setTimezone(config('app.timezone'))->diffForHumans(),
          'email'       => $this->email,
          'submitted_at' => $this->submitted_at
           ];
    }
}
