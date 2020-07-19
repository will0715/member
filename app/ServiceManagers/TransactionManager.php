<?php 

namespace App\ServiceManagers;

use App\Exceptions\ResourceNotFoundException;
use App\Services\MemberService;
use App\Services\BranchService;
use App\Services\ChopService;
use App\Services\TransactionService;
use App\Services\CalculateEarnChopsService;
use Arr;

class TransactionManager 
{

    private $memberService;
    private $branchService;
    private $chopService;

    public function __construct() 
    {
        $this->memberService = app(MemberService::class);
        $this->branchService = app(branchService::class);
        $this->chopService = app(ChopService::class);
        $this->transactionService = app(TransactionService::class);
        $this->calculateEarnChopsService = app(CalculateEarnChopsService::class);
    }

    public function listByMemberPhone($phone)
    {
        // search member
        $member = $this->memberService->findMemberByPhone($phone);
        
        $transaction = $this->transactionService->getByMemberId($member->id);

        return $transaction;
    }

    public function newTransaction($attributes)
    {
        $phone = $attributes['phone'];
        $branchId = $attributes['branch_id'];
        $orderId = $attributes['order_id'];

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        // search branch
        $branch = $this->branchService->findBranchByCode($branchId);

        // calculate chop
        $earnChopsData = $this->calculateEarnChopsService->calTransactionEarnChops($member, $attributes);
        
        // chops can't less than 1
        $earnChops = (int) $earnChopsData['chops'];
        $earnChopRule = $earnChopsData['used_chop_rule'];
        
        // add transaction
        $newTransactionRecord = $this->transactionService->newTransaction([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'chops' => $earnChops,
            'transaction' => $attributes
        ]);
        
        // add chop
        $record = $this->chopService->earnChops([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'chops' => $earnChops,
            'earn_chop_rule_id' => optional($earnChopRule)->id,
            'transaction_no' => $orderId,
            'remark' => $attributes['order_id']
        ]);

        return $newTransactionRecord;
    }

    public function newTransactionWithoutEarnChops($attributes)
    {
        $phone = $attributes['phone'];
        $branchId = $attributes['branch_id'];
        $earnChops = Arr::get($attributes, 'chops', null);

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        // search branch
        $branch = $this->branchService->findBranchByCode($branchId);
        
        // add transaction
        $newTransactionRecord = $this->transactionService->newTransaction([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'chops' => $earnChops,
            'transaction' => $attributes
        ]);

        return $newTransactionRecord;
    }

    public function voidTransaction($id, $attributes)
    {
        $transaction = $this->transactionService->findTransaction($id);

        $member = $transaction->member;
        $branch = $transaction->branch;
        $chopRecords = $transaction->chopRecords;

        // TODO: multi chop record
        $chopRecord = $chopRecords->first();

        // void transaction
        $newTransactionRecord = $this->transactionService->voidTransaction($id, $attributes);
        
        // void earn chop
        $record = $this->chopService->voidEarnChops($chopRecord->id);

        return $newTransactionRecord;
    }
}