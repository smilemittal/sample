<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isDropdown = $this->children->count() ? true : false;
        $iconClass = explode(" ", $this->icon_class ?? 'DocumentTextIcon');
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'iconClass'   => end($iconClass),
            'link'        => $this->route,
            'isDropdown'  => $isDropdown,
            'children' =>  new MenuItemCollection($this->children)
          ];
    }
}
