<?php

namespace App\Services;

use App\Constants\PaymentTypeConstant;
use App\Exceptions\AlreadyVoidedException;
use App\Exceptions\TransactionDuplicateException;
use App\Exceptions\ResourceNotFoundException;
use App\Criterias\LimitOffsetCriteria;
use App\Criterias\RequestDateRangeCriteria;
use App\Criterias\TransactionValidCriteria;
use App\Repositories\TransactionRepository;
use App\Repositories\TransactionItemRepository;
use App\Repositories\ChopRecordRepository;
use App\Repositories\ChopRepository;
use App\Repositories\RankRepository;
use App\Repositories\MemberRepository;
use App\Repositories\BranchRepository;
use App\Repositories\EarnChopRuleRepository;
use App\Models\Member;
use App\Helpers\TransactionUsedChopRuleHelper;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Cache;
use DB;
use Arr;

class TransactionService
{
    /** @var  TransactionRepository */
    private $transactionRepository;
    /** @var  ChopRepository */
    private $chopRepository;

    public function __construct($customer = '')
    {
        $this->transactionRepository = app(TransactionRepository::class);
        $this->transactionItemRepository = app(TransactionItemRepository::class);
        $this->chopRepository = app(ChopRepository::class);
        $this->chopRecordRepository = app(ChopRecordRepository::class);
        $this->earnChopRuleRepository = app(EarnChopRuleRepository::class);
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->chopRecordRepository = app(ChopRecordRepository::class);
    }

    public function listTransactions(Request $request)
    {
        $this->transactionRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->transactionRepository->pushCriteria(new RequestCriteria($request));
        $this->transactionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $transactions = $this->transactionRepository->all();

        return $transactions;
    }

    public function findTransaction($id)
    {
        $transaction = $this->transactionRepository->findWithoutFail($id);
        if (!$transaction) {
            throw new ResourceNotFoundException('Transaction not exist');
        }
        return $transaction;
    }

    public function findByOrderId($orderId)
    {
        $transaction = $this->transactionRepository->findByOrderId($orderId);
        return $transaction;
    }

    public function getByMemberId($memberId)
    {
        $transaction = $this->transactionRepository->getByMemberId($memberId);
        return $transaction;
    }

    public function newTransaction($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $transactionData = $attributes['transaction'];
        $transactionItems = collect(Arr::get($transactionData, 'items', []));
        $orderId = $transactionData['order_id'];
        $order = $this->findByOrderId($orderId);
        if ($order) {
            throw new TransactionDuplicateException($orderId . ' is already exist');
        }

        $transaction = $this->transactionRepository->createTransaction([
            'order_id' => $orderId,
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'payment_type' => Arr::get($transactionData, 'payment_type', ''),
            'clerk' => Arr::get($transactionData, 'clerk', ''),
            'items_count' => count($transactionItems),
            'amount' => Arr::get($transactionData, 'amount', 0),
            'remark' => Arr::get($transactionData, 'remark', ''),
            'status' => 1,
            'transaction_time' => Arr::get($transactionData, 'transaction_time', Carbon::now()),
        ], $transactionItems);

        // make transaction item
        // TODO: transaction data check
        $transactionItems = $transactionItems->map(function ($item, $key) {
            $orderItem = [
                'item_no' => $item['no'],
                'item_name' => $item['name'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
                'quantity' => $item['qty'],
                'item_condiments' => $item['condiments'] ?: ''
            ];
            return $orderItem;
        });

        $transaction->transactionItems()->createMany($transactionItems->toArray());

        return $transaction;
    }

    public function voidTransaction($id)
    {
        $transaction = $this->transactionRepository->find($id);
        if (!$transaction->isValid()) {
            throw new AlreadyVoidedException('Transaction already been voided', $transaction);
        }
        $voidTransaction = $this->transactionRepository->voidTransaction($transaction->id);

        return $voidTransaction;
    }

    // TODO: 加強累點功能
    public function calTransactionEarnChops(Member $member, $transactionData)
    {
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
