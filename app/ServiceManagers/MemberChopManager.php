<?php 

namespace App\ServiceManagers;

use App\Exceptions\ResourceNotFoundException;
use App\Services\MemberService;
use App\Services\BranchService;
use App\Services\ChopService;
use Arr;

class MemberChopServiceManager 
{

    private $memberService;
    private $branchService;
    private $chopService;

    public function __construct() 
    {
        $this->memberService = app(MemberService::class);
        $this->branchService = app(branchService::class);
        $this->chopService = app(ChopService::class);
    }

    public function getMemberWithChops($attributes)
    {
        $search = $attributes['search'];
        $branchId = $attributes['branch_id'];

        $member = $this->memberService->findMemberByQuerySearch($search);

        // search branch
        $branch = $this->branchService->findBranchByCode($branchId);

        // chops branch can use
        $chops = $this->chopService->getTotalChops($member->id, $branch->id);
        $member->totalChops = $chops;

        return $member;
    }

    public function getMemberChopsRecords($attributes)
    {
        $phone = $attributes['phone'];

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        $records = $this->chopService->findChopsRecordsByMember($member->id);

        return $records;
    }

    public function manualAddChops($attributes)
    {
        $phone = $attributes['phone'];
        $branchId = $attributes['branch_id'];
        $chops = $attributes['chops'];
        $remark = Arr::get($attributes, 'remark');
        $transactionNo = Arr::get($attributes, 'transaction_no');

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        // search branch
        $branch = $this->branchService->findBranchByCode($branchId);
        
        $record = $this->chopService->manualAddChops([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'chops' => $chops,
            'transaction_no' => $transactionNo,
            'remark' => $remark
        ]);

        return $record;
    }

    public function earnChops($attributes)
    {
        $phone = $attributes['phone'];
        $branchId = $attributes['branch_id'];
        $chops = $attributes['chops'];
        $earnChopsRuleId = Arr::get($attributes, 'earn_chop_rule_id');
        $remark = Arr::get($attributes, 'remark');
        $transactionNo = Arr::get($attributes, 'transaction_no');

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        // search branch
        $branch = $this->branchService->findBranchByCode($branchId);
        
        $record = $this->chopService->earnChops([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'chops' => $chops,
            'earn_chop_rule_id' => $earnChopsRuleId,
            'transaction_no' => $transactionNo,
            'remark' => $remark
        ]);

        return $record;
    }

    public function voidEarnChops($id, $attributes)
    {
        $record = $this->chopService->voidEarnChops($id, $attributes);

        return $record;
    }

    public function consumeChops($attributes)
    {
        $phone = $attributes['phone'];
        $branchId = $attributes['branch_id'];
        $chops = $attributes['chops'];
        $ruleId = Arr::get($attributes, 'rule_id', null);
        $remark = Arr::get($attributes, 'remark');
        $transactionNo = Arr::get($attributes, 'transaction_no');

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        // search branch
        $branch = $this->branchService->findBranchByCode($branchId);
        
        $record = $this->chopService->consumeChops([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'rule_id' => $ruleId,
            'chops' => $chops,
            'transaction_no' => $transactionNo,
            'remark' => $remark
        ]);

        return $record;
    }

    public function voidConsumeChops($id, $attributes)
    {
        $record = $this->chopService->voidConsumeChops($id, $attributes);

        return $record;
    }

    public function getMemberTotalChops($phone)
    {
        $member = $this->memberService->findMemberByPhone($phone);
        $chops = $this->chopService->listChopsByMemberId($member->id);

        return collect($chops)->sum('chops');
    }

    public function getMemberChopsDetail($phone)
    {
        $member = $this->memberService->findMemberByPhone($phone);
        $chops = $this->chopService->listChopsByMemberId($member->id);

        return $chops;
    }

    public function getMemberOrderDetail($phone)
    {
        $member = $this->memberService->findMemberByPhone($phone);

        return $member->orderRecords;
    }
}