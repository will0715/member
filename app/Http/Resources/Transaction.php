<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
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
            'order_id' => $resource->order_id,
            'payment_type' => $resource->payment_type,
            'clerk' => $resource->clerk,
            'items_count' => $resource->items_count,
            'amount' => $resource->amount,
            'remark' => $resource->remark,
            'status' => $resource->status,
            'transaction_time' => $resource->transaction_time,
            'created_at' => $resource->created_at,
            'branch' => new Branch($resource->whenLoaded('branch')),
            'member' => new Member($resource->whenLoaded('member')),
            'chop_records' => ChopRecord::collection($resource->whenLoaded('chopRecords')),
            'transaction_items' => TransactionItem::collection($resource->whenLoaded('transactionItems'))
        ];
    }
}