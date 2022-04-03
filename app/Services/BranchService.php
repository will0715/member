<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\BranchRepository;
use App\Exceptions\ResourceNotFoundException;
use Cache;

class BranchService
{
    /** @var  BranchRepository */
    private $branchRepository;

    public function __construct()
    {
        $this->branchRepository = app(BranchRepository::class);
    }

    public function listBranches($request)
    {
        $this->branchRepository->pushCriteria(new RequestCriteria($request));
        $this->branchRepository->pushCriteria(new LimitOffsetCriteria($request));
        $branches = $this->branchRepository->all();

        return $branches;
    }

    public function findBranch($id)
    {
        $branch = $this->branchRepository->findWithoutFail($id);
        if (!$branch) {
            throw new ResourceNotFoundException('Branch Not Found');
        }
        return $branch;
    }

    public function findBranchByCode($code)
    {
        $branch = $this->branchRepository->findByBranchId($code);
        if (!$branch) {
            throw new ResourceNotFoundException('Branch Not Found');
        }
        return $branch;
    }

    public function newBranch($data)
    {
        $branch = $this->branchRepository->create($data);
        return $branch;
    }

    public function updateBranch($data, $id)
    {
        $branch = $this->branchRepository->update($data, $id);
        return $branch;
    }

    public function deleteBranch($id)
    {
        return $this->branchRepository->delete($id);
    }

    public function getFirstBranch()
    {
        return $this->branchRepository->first();
    }
}
