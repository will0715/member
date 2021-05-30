<?php

namespace App\Criterias;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;

class PhoneCriteria implements CriteriaInterface
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

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
        $phone = $this->request->get('phone', null);

        if ($phone) {
            $model = $model->where('phone', $phone);
        }

        return $model;
    }
}