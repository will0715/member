<?php

namespace App\Repositories;

use App\Models\ConsumeChopRule;
use App\Repositories\BaseRepository;

/**
 * Class ConsumeChopRuleRepository
 * @package App\Repositories
 * @version April 7, 2020, 12:40 pm UTC
*/

class ConsumeChopRuleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rank_id',
        'type',
        'chops_per_unit',
        'unit_per_amount',
        'earn_chops_after_consume'
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
        return ConsumeChopRule::class;
    }
}
