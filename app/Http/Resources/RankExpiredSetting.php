<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RankExpiredSetting extends JsonResource
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
            'is_active' => $resource->is_active,
            'calculate_standard' => $resource->calculate_standard,
            'calculate_standard_value' => $resource->calculate_standard_value,
            'calculate_time_unit' => $resource->calculate_time_unit,
            'calculate_time_value' => $resource->calculate_time_value,
        ];
    }
}
