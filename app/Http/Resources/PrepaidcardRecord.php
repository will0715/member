<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrepaidcardRecord extends JsonResource
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
            'topup' => $resource->topup,
            'payment' => $resource->payment,
            'remark' => $resource->remark,
            'member' => new Member($resource->whenLoaded('member')),
            'branch' => new RecordBranch($resource->whenLoaded('branch')),
            'void_record' => new PrepaidcardRecord($resource->whenLoaded('voidRecord')),
            'voided_record' => new PrepaidcardRecord($resource->whenLoaded('voidedRecord')),
            'created_at' => $resource->created_at,
        ];
    }
}