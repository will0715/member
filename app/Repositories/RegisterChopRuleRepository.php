<?php

namespace App\Repositories;

use App\Models\RegisterChopRule;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

/**
 * Class RegisterChopRuleRepository
 * @package App\Repositories
 * @version April 7, 2020, 2:54 pm UTC
*/

class RegisterChopRuleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'rule_chops',
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
        return RegisterChopRule::class;
    }
}
