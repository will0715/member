<?php

namespace App\Services;

use App\Constants\PaymentTypeConstant;
use App\Exceptions\AlreadyVoidedException;
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
    /** @var  TransactionRepository */
    private $transactionRepository;
    /** @var  ChopRepository */
    private $chopRepository;

    public function __construct($customer = '')
    {
        $this->earnChopRuleRepository = app(EarnChopRuleRepository::class);
    }

    // TODO: 加強累點功能
    public function calTransactionEarnChops(Member $member, $transactionData)
    {
        // TODO: 開始結束時間
        $earnChopRules = $this->earnChopRuleRepository->findByRank($member->rank->id);
        $transactionPaymentType = Arr::get($transactionData, 'payment_type');

        $earnChops = 0;
        $usedChopRule = 0;
        foreach ($earnChopRules as $earnChopRule) {
            $chops = 0;
            if ($earnChopRule->payment_type === $transactionPaymentType || 
                $earnChopRule->payment_type === PaymentTypeConstant::PAYMENT_TYPE_ALL) {
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
