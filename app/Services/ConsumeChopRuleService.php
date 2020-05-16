<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\ConsumeChopRuleRepository;
use App\Helpers\CustomerHelper;
use App\Exceptions\ResourceNotFoundException;
use Cache;

class ConsumeChopRuleService
{

    public function __construct()
    {
        $this->consumeChopRuleRepository = app(ConsumeChopRuleRepository::class);
    }

    public function listConsumeChopRules($request)
    {
        $this->consumeChopRuleRepository->pushCriteria(new RequestCriteria($request));
        $this->consumeChopRuleRepository->pushCriteria(new LimitOffsetCriteria($request));
        $consumeChopRules = $this->consumeChopRuleRepository->all();

        return $consumeChopRules;
    }

    public function findConsumeChopRule($id)
    {
        $consumeChopRule = $this->consumeChopRuleRepository->findWithoutFail($id);
        return $consumeChopRule;
    }

    public function newConsumeChopRule($data)
    {
        $consumeChopRule = $this->consumeChopRuleRepository->create($data);
        return $consumeChopRule;
    }

    public function updateConsumeChopRule($data, $id)
    {
        $consumeChopRule = $this->consumeChopRuleRepository->update($data, $id);
        return $consumeChopRule;
    }

    public function deleteConsumeChopRule($id)
    {
        $consumeChopRule = $this->findConsumeChopRule($id);
        $consumeChopRule->delete();
        return $consumeChopRule;
    }
}
