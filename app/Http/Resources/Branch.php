<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Branch extends JsonResource
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
            'code' => $resource->code,
            'name' => $resource->name,
            'store_name' => $resource->store_name,
            'telphone' => $resource->telphone,
            'fax' => $resource->fax,
            'note' => $resource->note,
            'zipcode' => $resource->zipcode,
            'state' => $resource->state,
            'city' => $resource->city,
            'county' => $resource->county,
            'address' => $resource->address,
            'latitude' => $resource->latitude,
            'longitude' => $resource->longitude,
            'remark' => $resource->remark,
            'opening_times' => $resource->opening_times,
            'is_independent' => $resource->is_independent,
            'created_at' => $resource->created_at,
        ];
    }
}