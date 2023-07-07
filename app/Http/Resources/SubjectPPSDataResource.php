<?php

namespace App\Http\Resources;

use App\Models\Attachement;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class SubjectPPSDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      $images = explode(',', $this->images);
      return [
        'id' => $this->id,
        'shot' => $this->shot,
        'content' => $this->content,
        'type' => $this->type,
        'videos' => !empty($this->videos) ? Storage::url($this->videos) : $this->videos,
        'images' => strlen($this->images) > 0 ? array_map(function ($image) {
                      return Storage::url($image);
                  }, $images) : null
      ];
    }
}
