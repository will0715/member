<?php

namespace App\Repositories;

use App\Criterias\ChopsValidCriteria;
use App\Models\Chop;
use App\Models\ChopExpiredSetting;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

/**
 * Class ChopRepository
 * @package App\Repositories
 * @version April 7, 2020, 2:54 pm UTC
*/

class ChopRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member_id',
        'branch_id',
        'chops',
        'consume_chops',
        'status',
        'expired_at'
    ];

    public function boot()
    {
        $this->pushCriteria(new ChopsValidCriteria());
    }

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Chop::class;
    }

    public function getMemberChops($memberId)
    {
        return $this->findWhere([
            'member_id' => $memberId,
        ])->all();
    }

    public function getBranchChops($memberId, $branchId)
    {
        return $this->findWhere([
            'member_id' => $memberId,
            'branch_id' => $branchId,
        ])->first();
    }

    public function getMemberBranchesChops($memberId, $branchIds)
    {
        return $this->scopeQuery(function($query) use ($memberId, $branchIds) {
            return $query->whereIn('branch_id', $branchIds)->where('member_id', $memberId);
        });
    }
}
