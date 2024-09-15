<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Coupon extends JsonResource
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
        $couponGroup = $resource->couponGroup;

        return [
            'id' => $resource->id,
            'coupon_group_id' => $resource->coupon_group_id,
            'member_id' => $resource->member_id,
            'code' => $resource->code,
            'status' => $resource->status,
            'claimed_at' => $resource->claimed_at,
            'used_at' => $resource->used_at,
            'effective_start_at' => $resource->effective_start_at,
            'expired_at' => $resource->expired_at,
            'usage_data' => $resource->usage_data,
            'name' => $couponGroup->name,
            'can_trigger_others' => $couponGroup->can_trigger_others,
            'trigger_condition' => $couponGroup->trigger_condition,
            'content' => $couponGroup->content,
            'limit_branch' => $couponGroup->limit_branch,
            'created_at' => $resource->created_at,
            'member' => new Member($resource->whenLoaded('member')),
            'branches' => $couponGroup->limitBranches->pluck('code'),
        ];
    }
}
