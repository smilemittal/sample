<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Directory;
use App\Models\DirectoryModule;
use Illuminate\Support\Facades\Auth;

class DirectoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'encryptId'            => encrypt_tech($this->id),
            'name'       => $this->name,
            'parent_id'            => $this->parent_id,
            'perent' => $request->has('per_page') && $request->get('per_page') == 'all' ? null : new DirectoryResource($this->parent),
            'encrypyParentId'      => encrypt_tech($this->parent_id),
            'folderCount'          => Directory::getNoOfFolder($this->id, Auth::user()->company_id),
            'moduleCount'          => DirectoryModule::
                                      getNoOfModuleAssignedToFolder($this->id, Auth::user()->company_id),
            'createdAt'            =>\Carbon\Carbon::parse($this->created_at)->format('Y:m:d H:i:s'),
            'children'             => $request->has('per_page') && $request->get('per_page') == 'all' ? new DirectoryCollection($this->children) : null,
          ];
    }
}
