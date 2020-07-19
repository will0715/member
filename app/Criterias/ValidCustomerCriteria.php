<?php

namespace App\Criterias;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Carbon\Carbon;

class ValidCustomerCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository.
     *
     * @param $model
     * @param \Prettus\Repository\Contracts\RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, \Prettus\Repository\Contracts\RepositoryInterface $repository)
    {
        $now = Carbon::now();
        
        $model = $model
                ->where('status', '=', 1)
                ->where(function ($query) use ($now) {
                    $query->where('expired_at', '>=', $now)
                        ->orWhereNull('expired_at');
                });

        return $model;
    }
}