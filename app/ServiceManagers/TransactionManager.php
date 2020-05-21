<?php 

namespace App\ServiceManagers;

use App\Exceptions\ResourceNotFoundException;
use App\Services\MemberService;
use App\Services\BranchService;
use App\Services\ChopService;
use App\Services\TransactionService;
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
    }

    public function newTransaction($attributes)
    {
        $phone = $attributes['phone'];
        $branchId = $attributes['branch_id'];
        $ruleId = Arr::get($attributes, 'rule_id', null);

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        // search branch
        $branch = $this->branchService->findBranchByCode($branchId);
        
        // add transaction
        $newTransactionRecord = $this->transactionService->newTransaction([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'transaction' => $attributes
        ]);

        // calculate chop
        $earnChopsData = $this->transactionService->calTransactionEarnChops($member, $attributes);
        $earnChops = $earnChopsData['chops'];
        $earnChopRule = $earnChopsData['used_chop_rule'];
        
        // add chop
        $record = $this->chopService->earnChops([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'transaction_id' => $newTransactionRecord->id,
            'chops' => $earnChops,
            'earn_chop_rule_id' => optional($earnChopRule)->id
        ]);
        
        $newTransactionRecord->load(['chopRecords', 'transactionItems', 'chopRecords.earnChopRule']);

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