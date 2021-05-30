<?php

namespace App\Repositories;

use App\Models\PickupCouponConsumedHistory;
use App\Repositories\BaseRepository;

/**
 * Class PickupCouponConsumedHistoryRepository
 * @package App\Repositories
 * @version April 3, 2020, 5:35 am UTC
*/

class PickupCouponConsumedHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'phone',
        'product_name',
        'product_no',
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
        return PickupCouponConsumedHistory::class;
    }
}