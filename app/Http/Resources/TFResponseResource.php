<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\AssignModuleUpdation;

class TFResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       $json_decoded =  json_decode($this->json, true);
        return [
            'id'            => $this->id,
            'email'    => $this->email,
            'typeform_id'   => $this->typeform_id,
            'created_at'    => \Carbon\Carbon::parse($this->created_at)->format('Y:m:d H:i:s'),
            'submitted_at'    => \Carbon\Carbon::parse($this->submitted_at)->format('d/m/Y h:i:s a'),
            'name' => $this->name,
            'questions' => $json_decoded['definition']['fields'] ?? "",
            'answers' => $json_decoded['answers'] ?? "",
            'hidden' => $json_decoded['hidden'] ?? "",
            'submittedAt' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->submitted_at)
          ->setTimezone(config('app.timezone'))->diffForHumans(),
        ];
    }
}
