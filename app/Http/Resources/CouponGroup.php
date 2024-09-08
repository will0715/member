<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponGroup extends JsonResource
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
            'prefix_code' => $resource->prefix_code,
            'limit_branch' => $resource->limit_branch,
            'calculate_time_unit' => $resource->calculate_time_unit,
            'fixed_start_time' => $resource->fixed_start_time,
            'fixed_end_time' => $resource->fixed_end_time,
            'valid_days_after_claim' => $resource->valid_days_after_claim,
            'can_trigger_others' => $resource->can_trigger_others,
            'trigger_condition' => $resource->trigger_condition,
            'content' => $resource->content,
            'branches' => Branch::collection($resource->whenLoaded('limitBranches')),
        ];
    }
}
