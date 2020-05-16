<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberByQuery extends JsonResource
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
            'phone' => $resource->phone,
            'first_name' => $resource->first_name,
            'last_name' => $resource->last_name,
            'gender' => $resource->gender,
            'email' => $resource->email,
            'address' => $resource->address,
            'remark' => $resource->remark,
            'status' => $resource->status,
            'birthday' => $resource->birthday,
            'rank' => new Rank($resource->whenLoaded('rank')),
            'totalChops' => $resource->totalChops,
            'created_at' => $resource->created_at,
        ];
    }
}