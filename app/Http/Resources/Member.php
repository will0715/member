<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Member extends JsonResource
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
            'card_carrier_no' => $resource->card_carrier_no,
            'invoice_carrier_no' => $resource->invoice_carrier_no,
            'rank' => new Rank($resource->whenLoaded('rank')),
            'chops' => Chop::collection($resource->whenLoaded('chops')),
            'order_records' => Transaction::collection($resource->whenLoaded('orderRecords')),
            'chop_records' => ChopRecord::collection($resource->whenLoaded('chopRecords')),
            'prepaidcard' => new Prepaidcard($resource->whenLoaded('prepaidcard')),
            'prepaidcard_records' => PrepaidcardRecord::collection($resource->whenLoaded('prepaidcardRecords')),
            'created_at' => $resource->created_at,
            'register_branch' => new Branch($resource->whenLoaded('registerBranch')),
            'rank_expired_at' => $resource->rank_expired_at,
        ];
    }
}
