<?php 

namespace App\ServiceManagers;

use App\Constants\MemberConstant;
use App\Exceptions\ResourceNotFoundException;
use App\Services\MemberService;
use App\Services\BranchService;
use App\Services\ChopService;
use App\Services\RankService;
use App\Services\TransactionService;
use Arr;

class MemberRegisterManager 
{

    private $memberService;
    private $branchService;
    private $chopService;
    private $rankService;

    public function __construct() 
    {
        $this->memberService = app(MemberService::class);
        $this->branchService = app(branchService::class);
        $this->chopService = app(ChopService::class);
        $this->rankService = app(RankService::class);
        $this->transactionService = app(TransactionService::class);
    }

    public function registerMember($attributes)
    {
        $phone = $attributes['phone'];
        $branchId = Arr::get($attributes, 'branch_id', null);
        $rankId = Arr::get($attributes, 'rank_id', null);

        if (!$rankId) {
            $basicRank = $this->rankService->getBasicRank();
            $attributes['rank_id'] = $basicRank->id;
        }
        if ($branchId) {
            // search branch
            $branch = $this->branchService->findBranchByCode($branchId);
            $attributes['register_branch_id'] = $branch->id;
        }
        
        $member = $this->memberService->newMember($attributes);
        $member->load(MemberConstant::ALL_MEMBER_RELATIONS);

        return $member;
    }
}