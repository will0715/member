<?php

namespace App\Criterias;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Carbon\Carbon;

class RequestDateRangeCriteria implements CriteriaInterface
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
        $start = $this->request->get('start', null);
        $end = $this->request->get('end', null);

        if ($start) {
            $model = $model->where('created_at', '>=', $start);
        }

        if ($start && $end) {
            $model = $model->where('created_at', '<', $end);
        }

        return $model;
    }
}