<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RankDiscount extends JsonResource
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
            'content' => $resource->content,
            'is_active' => $resource->is_active,
            'rank' => new Rank($resource->whenLoaded('rank'))
        ];
    }
}