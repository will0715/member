<?php

namespace App\Repositories;

use App\Models\PickupCoupon;
use App\Repositories\BaseRepository;

/**
 * Class PickupCouponRepository
 * @package App\Repositories
 * @version April 3, 2020, 5:35 am UTC
*/

class PickupCouponRepository extends BaseRepository
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
        return PickupCoupon::class;
    }

    public function findByPhone($phone)
    {
        return $this->findWhere(['phone' => $phone]);
    }

    public function findByCode($code)
    {
        return $this->findByField('code', $code)->first();
    }
}

