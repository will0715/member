<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Repositories\BaseRepository;

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
}
