<?php

namespace App\Criterias;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use App\Criterias\BooleanCriteria;

class PickupCouponRemainedCriteria extends BooleanCriteria implements CriteriaInterface
{

    public function trueScope($model, \Prettus\Repository\Contracts\RepositoryInterface $repository)
    {
        return $model->whereColumn('quantity', '>', 'consumed_quantity');
    }

    public function falseScope($model, \Prettus\Repository\Contracts\RepositoryInterface $repository)
    {
        return $model->whereColumn('quantity', '<=', 'consumed_quantity');
    }
}