<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionItem extends JsonResource
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
            'item_no' => $resource->item_no,
            'item_name' => $resource->item_name,
            'quantity' => $resource->quantity,
            'price' => $resource->price,
            'subtotal' => $resource->subtotal,
            'remark' => $resource->remark,
            'transaction' => new Transaction($resource->whenLoaded('transaction')),
            'condiments' => TransactionItemCondiment::collection($resource->whenLoaded('condiments'))
        ];
    }
}