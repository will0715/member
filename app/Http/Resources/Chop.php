<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Chop extends JsonResource
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
            'chops' => $resource->chops,
            'branch' => new Branch($resource->branch),
            'expired_at' => $resource->expired_at,
        ];
    }
}