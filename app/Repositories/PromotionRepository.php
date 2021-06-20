<?php

namespace App\Repositories;

use App\Models\Promotion;
use App\Repositories\BaseRepository;
use App\Constants\PromotionConstant;

/**
 * Class PromotionRepository
 * @package App\Repositories
 * @version April 2, 2020, 4:32 pm UTC
*/

class PromotionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'name' => 'like',
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
        return Promotion::class;
    }

    public function findByType($type)
    {
        return $this->findByField('type', $type);
    }

    public function findPOSPromotions()
    {
        return $this->findByType(PromotionConstant::TYPE_POS);
    }

    public function findOnlinePromotions()
    {
        return $this->findByType(PromotionConstant::TYPE_ONLINE);
    }
}
