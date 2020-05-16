<?php

namespace App\Helpers;

use App\Repositories\EarnChopRuleRepository;
use Arr;

class TransactionUsedChopRuleHelper
{
    private $earnChopRuleRepository;
    private $member;
    private $transactionData;
    public $earnChops = 0;
    public $usedChopRule;

    public function __construct($member, $transactionData)
    {
        $this->earnChopRuleRepository = app(EarnChopRuleRepository::class);
        $this->member = $member;
        $this->transactionData = $transactionData;
    }

    public function getUsedChopRule()
    {
        return $this->usedChopRule;
    }

    public function calTransactionEarnChops()
    {
        $member = $this->member;
        $transactionData = $this->transactionData;
        $earnChopRules = $this->earnChopRuleRepository->findByRank($member->rank->id);
        $transactionPaymentType = Arr::get($transactionData, 'payment_type');

        // TODO: 積點規則
        foreach ($earnChopRules as $earnChopRule) {
            $chops = 0;
            if ($earnChopRule->payment_type === $transactionPaymentType) {
                $ruleUnit = $earnChopRule->rule_unit;
                $ruleChops = $earnChopRule->rule_chops;
                // TODO: exclude_product
                switch($earnChopRule->type) {
                    case "AMOUNT":
                        $amount = Arr::get($transactionData, 'amount');
                        $chops = $amount / $ruleUnit * $ruleChops;
                        break;
                    case "ITEM_COUNT":
                        $itemCount = Arr::get($transactionData, 'items_count');
                        $chops = $itemCount / $ruleUnit * $ruleChops;
                        break;
                    default:
                        $ruleChops = 0;
                        break;
                }
                if ($this->earnChops < $chops) {
                    $this->earnChops = $chops;
                    $this->usedChopRule = $earnChopRule;
                }
            }
        }
        return $this->earnChops ?: 0;
    }
}
