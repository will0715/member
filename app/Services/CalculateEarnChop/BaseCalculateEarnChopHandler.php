<?php

namespace App\Services;

use App\Repositories\EarnChopRuleRepository;
use App\Models\EarnChopRule;

abstract class BaseCalculateEarnChopHandler
{
    protected $earnChopRule;

    public function __construct(EarnChopRule $earnChopRule)
    {
        $this->earnChopRuleRepository = app(EarnChopRuleRepository::class);
        $this->earnChopRule = $earnChopRuleㄤ;
    }

    protected function calculate($transactionData){}
}
