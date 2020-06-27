<?php

namespace App\Repositories;

use App\Models\Rank;
use App\Repositories\BaseRepository;

/**
 * Class RankRepository
 * @package App\Repositories
 * @version April 3, 2020, 5:35 am UTC
*/

class RankRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rank',
        'name'
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
        return Rank::class;
    }

    public function getBasicRank()
    {
        return $this->orderBy('rank')->first();
    }

    public function getWithMemberCount()
    {
        return $this->withCount('members')->all();
    }
}
