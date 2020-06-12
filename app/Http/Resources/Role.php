<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Role extends JsonResource
{
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this;

        return [
            'id' => $resource->id,
            'name' => $resource->name,
            'created_at' => $resource->created_at,
            'permissions' => Permission::collection($resource->whenLoaded('permissions'))
        ];
    }
}