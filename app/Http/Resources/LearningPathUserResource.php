<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\LearningPathForm;
use App\Models\LearningPathHistory;
use App\Models\UserLearningPath;

class LearningPathUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avatarurl = '/';
        $this->user->avatar ? ($avatarurl .= $this->user->avatar) : ($avatarurl = 'images/placeholder-profile.png');
            return [
            'id'         => $this->id,
            'userName'   =>  $this->user->name,
            'userEmail'   => $this->user->email,
            'scheduleDate' => $this->learningpath_schduled_at,
            'created_at'  => \Carbon\Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'image'        => $avatarurl,
            'userId'       => $this->user->id,
            'archived_at'  =>  $this->archived_at
          ];
    }
}
