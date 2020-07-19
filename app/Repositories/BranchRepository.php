<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use DB;

/**
 * Class BranchRepository
 * @package App\Repositories
 * @version April 3, 2020, 5:23 am UTC
*/

class BranchRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'name',
        'store_name',
        'email',
        'telphone',
        'fax',
        'note',
        'zipcode',
        'state',
        'city',
        'county',
        'address',
        'latitude',
        'longitude',
        'remark',
        'opening_times'
    ];

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
        return Branch::class;
    }

    public function findByBranchId($branchId)
    {
        return $this->findByField('code', $branchId)->first();
    }

    public function getUnindependentBranches()
    {
        return $this->findByField('is_independent', false);
    }

    public function withRegisterMemberCount($startAt = null, $endAt = null)
    {
        if (!$startAt || !$endAt){
            return $this->withCount(['registerMembers']);
        }
        return $this->withCount(['registerMembers' => function ($query) use ($startAt, $endAt) {
            $query->where('created_at', '>=', $startAt)
                    ->where('created_at', '<', $endAt);
        }]);
    }

    public function getWithNewRegisterMember()
    {
        return $this->withCount(['registerMembers' => function ($query) {
            $query->where('created_at', '>=', Carbon::now()->startOfDay());
        }])->all();
    }

    public function getWithOldRegisterMember()
    {
        return $this->withCount(['registerMembers' => function ($query) {
            $query->where('created_at', '<', Carbon::now()->startOfDay());
        }])->all();
    }
}
