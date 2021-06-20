<?php

namespace App\Criterias;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;

class PromotionBranchCriteria implements CriteriaInterface
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
        $branch = $this->request->get('branch', null);

        if ($branch) {
            $model = $model->where(function ($query) use ($branch) {
                $query->where('limit_branch', false)->orWhereHas('limitBranches', function ($query) use ($branch) {
                    $query->where('code', $branch);
                });
            });
        }

        return $model;
    }
}