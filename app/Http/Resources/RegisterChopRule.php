<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterChopRule extends JsonResource
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
            'is_active' => $resource->is_active,
            'name' => $resource->name,
            'description' => $resource->description,
            'rule_chops' => $resource->rule_chops,
        ];
    }
}