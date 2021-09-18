<?php

namespace App\Repositories;

use App\Models\RankDiscount;
use App\Repositories\BaseRepository;

/**
 * Class RankDiscountRepository
 * @package App\Repositories
 * @version April 3, 2020, 5:35 am UTC
*/

class RankDiscountRepository extends BaseRepository
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
        return RankDiscount::class;
    }

    public function getDefaultRankDiscount($rankId)
    {
        // $rankDiscount = new RankDiscount;
        // $rankDiscount->rankId = 
        return RankDiscount::create([
            'rank_id' => $rankId,
            'is_active' => RankDiscount::IS_UNACTIVE,
            'content' => RankDiscount::DEFAULT_CONTENT,
        ]);
    }

    public function findByRankId($rankId)
    {
        return $this->findByField('rank_id', $rankId)->first();
    }
}
