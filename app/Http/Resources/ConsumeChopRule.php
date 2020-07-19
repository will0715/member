<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsumeChopRule extends JsonResource
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
            'description' => $resource->description,
            'type' => $resource->type,
            'chops_per_unit' => $resource->chops_per_unit,
            'unit_per_amount' => $resource->unit_per_amount,
            'exclude_product' => $resource->exclude_product,
            'consume_max_percentage' => $resource->consume_max_percentage,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
            'activated_at' => $resource->activated_at,
            'expired_at' => $resource->expired_at,
            'payment_type' => $resource->payment_type,
            'rank' => new Rank($resource->whenLoaded('rank')),
        ];
    }
}