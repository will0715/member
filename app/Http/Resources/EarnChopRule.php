<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EarnChopRule extends JsonResource
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
            'payment_type' => $resource->payment_type,
            'rule_unit' => $resource->rule_unit,
            'rule_chops' => $resource->rule_chops,
            'exclude_product' => $resource->exclude_product,
            'exclude_destination' => $resource->exclude_destination,
            'earn_chops_after_consume' => $resource->earn_chops_after_consume,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
            'activated_at' => $resource->activated_at,
            'expired_at' => $resource->expired_at,
            'rank' => new Rank($resource->whenLoaded('rank')),
        ];
    }
}