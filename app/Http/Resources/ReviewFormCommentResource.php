<?php

namespace App\Http\Resources;

use App\Models\Attachement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewFormCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userRole = '';
        $user = User::with('role')->find($this->user_id);
        if ($user->role->name == 'superadmin' ) {
            $userRole = 'SA';
        } elseif ($user->role->name == 'company') {
            $userRole = 'CA';
        } elseif ($user->role->name == 'businessadmin' ) {
            $userRole = 'BA';
        }
        return [
            'id'                   => $this->id,
            'comments'             => $this->comments,
            'form_id'              => $this->form_id,
            'userRole'              => $userRole,
            'files'                => explode (",", $this->images),
            'createdAt'            => $this->created_at->diffForHumans(),
            'userName'             => $user->name,
          ];
    }
}
