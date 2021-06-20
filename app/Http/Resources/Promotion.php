<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Promotion extends JsonResource
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
            'code' => $resource->code,
            'name' => $resource->name,
            'type' => $resource->type,
            'sequence' => $resource->sequence,
            'limit_branch' => $resource->limit_branch,
            'limit_rank' => $resource->limit_rank,
            'activated_date_start' => $resource->activated_date_start,
            'activated_date_end' => $resource->activated_date_end,
            'activated_time_start' => $resource->activated_time_start,
            'activated_time_end' => $resource->activated_time_end,
            'activated_weekday' => $resource->activated_weekday,
            'activated_monthday' => $resource->activated_monthday,
            'can_trigger_others' => $resource->can_trigger_others,
            'trigger_condition' => $resource->trigger_condition,
            'content' => $resource->content,
            'branches' => Branch::collection($resource->whenLoaded('limitBranches')),
            'ranks' => Rank::collection($resource->whenLoaded('limitRanks'))
        ];
    }
}