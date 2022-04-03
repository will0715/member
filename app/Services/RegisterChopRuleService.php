<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\RegisterChopRuleRepository;
use App\Exceptions\ResourceNotFoundException;
use App\Models\RegisterChopRule;
use Cache;
use Arr;

class RegisterChopRuleService
{

    public function __construct()
    {
        $this->registerChopRuleRepository = app(RegisterChopRuleRepository::class);
    }

    public function listRegisterChopRules($request)
    {
        $this->registerChopRuleRepository->pushCriteria(new RequestCriteria($request));
        $this->registerChopRuleRepository->pushCriteria(new LimitOffsetCriteria($request));
        $registerChopRules = $this->registerChopRuleRepository->all();

        return $registerChopRules;
    }

    public function findRegisterChopRule($id)
    {
        $registerChopRule = $this->registerChopRuleRepository->findWithoutFail($id);
        if (!$registerChopRule) {
            throw new ResourceNotFoundException('RegisterChopRule Not Found');
        }
        return $registerChopRule;
    }

    public function newRegisterChopRule($data)
    {
        $registerChopRule = $this->registerChopRuleRepository->create($data);
        return $registerChopRule;
    }

    public function updateRegisterChopRule($data, $id)
    {
        $registerChopRule = $this->registerChopRuleRepository->update($data, $id);
        return $registerChopRule;
    }

    public function getRegisterChopRule()
    {
        $registerChopRule = $this->registerChopRuleRepository->first();
        return $registerChopRule ?: new RegisterChopRule();
    }

    public function setRegisterChopRule($data)
    {
        $registerChopRule = $this->registerChopRuleRepository->first();
        
        $registerChopRule = $this->registerChopRuleRepository->updateOrCreate(
            ['id' => optional($registerChopRule)->id],
            $data
        );
        return $registerChopRule;
    }

    public function deleteRegisterChopRule($id)
    {
        return $this->registerChopRuleRepository->delete($id);
    }
}
