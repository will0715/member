<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ChopRecord;
use Arr;

class ConsumeChopSummaryRecord extends JsonResource
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
            'id' => Arr::get($resource, 'id'),
            'type' => Arr::get($resource, 'type'),
            'chops' => Arr::get($resource, 'chops'),
            'consume_chops' => Arr::get($resource, 'consume_chops'),
            'remark' => Arr::get($resource, 'remark'),
            'transaction_no' => Arr::get($resource, 'transaction_no'),
            'created_at' => Arr::get($resource, 'created_at'),
            'records' => ChopRecord::collection(Arr::get($resource, 'records'))
        ];
    }
}
