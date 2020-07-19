<?php

namespace App\Services;

use App\Criterias\ChopRuleValidCriteria;
use App\Constants\PaymentTypeConstant;
use App\Exceptions\TransactionDuplicateException;
use App\Exceptions\ResourceNotFoundException;
use App\Repositories\EarnChopRuleRepository;
use App\Models\Member;
use App\Helpers\TransactionUsedChopRuleHelper;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Cache;
use DB;
use Arr;
use Str;
use Log;

class CalculateEarnChopsService
{

    public function __construct()
    {
        $this->earnChopRuleRepository = app(EarnChopRuleRepository::class);
    }

    public function calTransactionEarnChops(Member $member, $transactionData)
    {
        // get rules
        $this->earnChopRuleRepository->pushCriteria(new ChopRuleValidCriteria());
        $earnChopRules = $this->earnChopRuleRepository->findByRank($member->rank->id);

        $transactionPaymentType = Arr::get($transactionData, 'payment_type');
        $transactionItems = Arr::get($transactionData, 'items');
        $consumeChops = Arr::get($transactionData, 'consume_chops');

        // TODO: 架構調整 - to abstract factory pattern
        $earnChops = 0;
        $usedChopRule = 0;
        foreach ($earnChopRules as $earnChopRule) {
            $chops = 0;

            // 兌點後無法繼續累點
            if ($earnChopRule->earn_chops_after_consume && $consumeChops != 0) {
                continue;
            }

            if ($earnChopRule->payment_type === $transactionPaymentType || 
                $earnChopRule->payment_type === PaymentTypeConstant::PAYMENT_TYPE_ALL) {
                $ruleUnit = $earnChopRule->rule_unit;
                $ruleChops = $earnChopRule->rule_chops;

                // get exclude product item
                $excludeProducts = $earnChopRule->exclude_product ? explode(',', $earnChopRule->exclude_product) : [];
                $excludeProductItems = collect($transactionItems)->whereIn('no', $excludeProducts);

                switch($earnChopRule->type) {
                    case "AMOUNT":
                        $amount = Arr::get($transactionData, 'amount');
                        $excludeProductAmount = $excludeProductItems->pluck('subtotal')->sum();
                        $canEarnChopAmount = ($amount - $excludeProductAmount >= 0) ? $amount - $excludeProductAmount : 0;
                        $chops = ($canEarnChopAmount) / $ruleUnit * $ruleChops;
                        break;
                    case "ITEM_COUNT":
                        $itemCount = Arr::get($transactionData, 'items_count');
                        $excludeProductQty = $excludeProductItems->pluck('qty')->sum();
                        $canEarnChopQty = ($itemCount - $excludeProductQty >= 0) ? $itemCount - $excludeProductQty : 0;
                        $chops = ($canEarnChopQty) / $ruleUnit * $ruleChops;
                        break;
                    default:
                        $ruleChops = 0;
                        break;
                }
                if ($earnChops < $chops) {
                    $earnChops = $chops;
                    $usedChopRule = $earnChopRule;
                }
            }
        }
        return [
            'chops' => $earnChops ?: 0,
            'used_chop_rule' => $usedChopRule
        ];
    }
}
