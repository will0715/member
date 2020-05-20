<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\EarnChopRuleRepository;
use App\Helpers\CustomerHelper;
use App\Exceptions\ResourceNotFoundException;
use Cache;

class EarnChopRuleService
{

    public function __construct()
    {
        $this->earnChopRuleRepository = app(EarnChopRuleRepository::class);
    }

    public function listEarnChopRules($request)
    {
        $this->earnChopRuleRepository->pushCriteria(new RequestCriteria($request));
        $this->earnChopRuleRepository->pushCriteria(new LimitOffsetCriteria($request));
        $earnChopRules = $this->earnChopRuleRepository->all();

        return $earnChopRules;
    }

    public function findEarnChopRule($id)
    {
        $earnChopRule = $this->earnChopRuleRepository->findWithoutFail($id);
        if (!$earnChopRule) {
            throw new ResourceNotFoundException('Earn Consume rule Not Found');
        }
        return $earnChopRule;
    }

    public function newEarnChopRule($data)
    {
        $earnChopRule = $this->earnChopRuleRepository->create($data);
        return $earnChopRule;
    }

    public function updateEarnChopRule($data, $id)
    {
        $earnChopRule = $this->earnChopRuleRepository->update($data, $id);
        return $earnChopRule;
    }

    public function deleteEarnChopRule($id)
    {
        $earnChopRule = $this->findEarnChopRule($id);
        $earnChopRule->delete();
        return $earnChopRule;
    }
}
