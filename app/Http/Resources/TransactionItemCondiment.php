<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionItemCondiment extends JsonResource
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
            'no' => $resource->no,
            'name' => $resource->name,
            'quantity' => $resource->quantity,
            'price' => $resource->price,
            'subtotal' => $resource->subtotal,
            'remark' => $resource->remark,
        ];
    }
}