<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChopRecord extends JsonResource
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
            'type' => $resource->type,
            'chops' => $resource->chops,
            'consume_chops' => $resource->consume_chops,
            'remark' => $resource->remark,
            'transaction_no' => $resource->transaction_no,
            'member' => new Member($resource->whenLoaded('member')),
            'branch' => new RecordBranch($resource->whenLoaded('branch')),
            'transaction' => new Transaction($resource->whenLoaded('transaction')),
            'void_record' => new ChopRecord($resource->whenLoaded('voidRecord')),
            'voided_record' => new ChopRecord($resource->whenLoaded('voidedRecord')),
            'created_at' => $resource->created_at,
        ];
    }
}