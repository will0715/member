<?php

namespace App\Services;

use App\Exceptions\TransactionDuplicateException;
use App\Criterias\TransactionValidCriteria;
use App\Models\Member;
use App\Repositories\TransactionRepository;
use App\Repositories\ChopRecordRepository;
use App\Repositories\ChopRepository;
use App\Repositories\RankRepository;
use App\Repositories\MemberRepository;
use App\Repositories\BranchRepository;
use App\Repositories\EarnChopRuleRepository;
use App\Helpers\TransactionUsedChopRuleHelper;
use App\Events\MemberRegistered;
use Carbon\Carbon;
use Exception;
use Cache;
use DB;
use Arr;

class TransactionService
{
    /** @var  TransactionRepository */
    private $transactionRepository;
    /** @var  ChopRepository */
    private $chopRepository;
    private $customer;
    private $member;
    private $branch;

    public function __construct($customer = '')
    {
        $this->transactionRepository = app(TransactionRepository::class);
        $this->chopRepository = app(ChopRepository::class);
        $this->chopRecordRepository = app(ChopRecordRepository::class);
        $this->earnChopRuleRepository = app(EarnChopRuleRepository::class);
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->chopRecordRepository = app(ChopRecordRepository::class);
        $this->customer = $customer;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    public function setMember($member)
    {
        $this->member = $member;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    public function newTransaction($transactionData)
    {
        $transactionItems = collect(Arr::get($transactionData, 'items', []));
        $orderId = $transactionData['order_id'];
        $order = $this->transactionRepository->getByOrderId($orderId);
        if ($order) {
            throw new TransactionDuplicateException($orderId);
        }
        
        $member = $this->member;
        $branch = $this->branch;
        // calculate chop
        // TODO : 架構調整 Transaction Service與 Chop Service 合併 Or 移至Event
        $usedChposRuleHelper = new TransactionUsedChopRuleHelper($member, $transactionData);
        $earnChops = $usedChposRuleHelper->calTransactionEarnChops();
        $earnChopRule = $usedChposRuleHelper->getUsedChopRule();

        $transaction = null;
        DB::beginTransaction();
        try {

            $transaction = $this->transactionRepository->createTransaction([
                'order_id' => $orderId,
                'member_id' => $member->id,
                'branch_id' => $branch->id,
                'payment_type' => Arr::get($transactionData, 'payment_type', ''),
                'clerk' => Arr::get($transactionData, 'clerk', ''),
                'items_count' => $transactionItems,
                'amount' => Arr::get($transactionData, 'amount', 0),
                'remark' => Arr::get($transactionData, 'remark', ''),
                'status' => 1,
                'transaction_time' => Arr::get($transactionData, 'transaction_time', Carbon::now()),
            ], $transactionItems);

            $this->chopRepository->addChops($member, $branch, $earnChops);
            
            // add chop record
            $record = $this->chopRecordRepository->newEarnChopRecord([
                'member' => $member,
                'branch' => $branch,
                'chops' => $earnChops,
                'earnChopRule' => $earnChopRule,
                'transaction' => $transaction
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        $transaction = $this->transactionRepository->with(['chopRecords', 'transactionItems', 'chopRecords.earnChopRule'])->find($transaction->id);

        return $transaction;
    }

    public function voidTransaction($id)
    {
        $transaction = $this->transactionRepository->find($id);
        if (!$transaction) {
            throw new Exception('Can\'t not find the transcation');
        }
        if (!$transaction->isValid()) {
            throw new Exception('Transaction already been voided');
        }

        $member = $transaction->member;
        $branch = $transaction->branch;
        $records = $transaction->chopRecords;
        // TODO: multi chop record
        $record = $records->first();
        DB::beginTransaction();
        try {
            $transaction = $this->transactionRepository->voidTransaction($transaction->id);
            if ($record) {
                $chops = -1 * $record->chops;
    
                // add chops
                $this->chopRepository->addChops($member, $branch, $chops);
     
                // add void record
                $voidRecord = $this->chopRecordRepository->voidRecord($record->id);
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
        }

        return $transaction;
    }

    public function calTransactionEarnChops($transactionData)
    {
        $member = $this->member;
        $earnChopRules = $this->earnChopRuleRepository->findByRank($member->rank->id);
        $transactionPaymentType = Arr::get($transactionData, 'payment_type');

        $earnChops = 0;
        $usedChopRule = 0;
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
                if ($earnChops < $chops) {
                    $earnChops = $chops;
                    $usedChopRule = $earnChopRule;
                }
            }
        }
        return $earnChops ?: 0;
    }
}
