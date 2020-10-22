<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberList extends JsonResource
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
            'register_branch' => new Branch($resource->whenLoaded('registerBranch')),
            'chops_total' => (float)$resource->chops_total,
            'prepaidcard_balance' => (float)$resource->prepaidcard_balance,
            'created_at' => $resource->created_at,
        ];
    }
}