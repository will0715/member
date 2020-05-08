<?php

namespace App\Repositories;

use App\Models\EarnChopRule;
use App\Repositories\BaseRepository;

/**
 * Class EarnChopRuleRepository
 * @package App\Repositories
 * @version April 7, 2020, 12:42 pm UTC
*/

class EarnChopRuleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rank_id',
        'name',
        'description',
        'payment_type',
        'type',
        'rule_unit',
        'rule_chops',
        'exclude_product'
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
        return EarnChopRule::class;
    }

    public function findByRank($rankId)
    {
        return $this->findWhere(['rank_id' => $rankId]);
    }
}
