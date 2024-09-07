<?php

namespace App\Repositories;

use App\Models\CouponGroup;
use App\Repositories\BaseRepository;

class CouponGroupRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
        'prefix_code' => 'like',
        'calculate_time_unit',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return CouponGroup::class;
    }

    public function findByPrefixCode($prefixCode)
    {
        return $this->findByField('prefix_code', $prefixCode)->first();
    }

    public function getActiveGroups()
    {
        $now = now();
        return $this->model->where(function ($query) use ($now) {
            $query->where('calculate_time_unit', 'fixed')
                  ->where('fixed_start_time', '<=', $now)
                  ->where('fixed_end_time', '>=', $now);
        })->orWhere('calculate_time_unit', 'claim')->get();
    }

    public function createWithRelations(array $input, array $rankIds = [], array $branchIds = [])
    {
        $couponGroup = $this->create($input);

        if (!empty($rankIds)) {
            $couponGroup->limitRanks()->sync($rankIds);
        }

        if (!empty($branchIds)) {
            $couponGroup->limitBranches()->sync($branchIds);
        }

        return $couponGroup;
    }

    public function updateWithRelations($id, array $input, array $rankIds = [], array $branchIds = [])
    {
        $couponGroup = $this->update($input, $id);

        if (isset($rankIds)) {
            $couponGroup->limitRanks()->sync($rankIds);
        }

        if (isset($branchIds)) {
            $couponGroup->limitBranches()->sync($branchIds);
        }

        return $couponGroup;
    }
}
