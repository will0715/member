<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PickupCoupon extends JsonResource
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
            'product_name' => $resource->product_name,
            'product_no' => $resource->product_no,
            'code' => $resource->code,
            'quantity' => $resource->quantity,
            'consumed_quantity' => $resource->consumed_quantity,
            'price' => $resource->price,
            'condiments' => $resource->condiments,
            'limit_branch' => $resource->limit_branch,
            'remark' => $resource->remark,
            'expired_at' => $resource->expired_at,
            'last_consumed_at' => $resource->last_consumed_at,
            'created_at' => $resource->created_at,
            'limit_branches' => Branch::collection($resource->whenLoaded('limitBranches'))
        ];
    }
}