<?php

namespace App\Services;

use App\Exceptions\ChopsNotEnoughException;
use App\Exceptions\AlreadyVoidedException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Member;
use App\Repositories\ChopExpiredSettingRepository;
use App\Repositories\ChopRecordRepository;
use App\Repositories\ChopRepository;
use App\Repositories\RankRepository;
use App\Repositories\MemberRepository;
use App\Repositories\BranchRepository;
use App\Repositories\TransactionRepository;
use App\Events\MemberRegistered;
use Cache;
use DB;
use Illuminate\Http\Request;

class ReportService
{
    /** @var  TransactionRepository */
    private $transactionRepository;
    /** @var  ChopRepository */
    private $chopRepository;
    private $customer;
    private $user;
    private $branch;

    public function __construct($customer = '')
    {
        $this->chopRepository = app(ChopRepository::class);
        $this->chopRecordRepository = app(ChopRecordRepository::class);
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->transactionRepository = app(TransactionRepository::class);
        $this->rankRepository = app(RankRepository::class);
        $this->customer = $customer;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    public function setUser($user)
    {
        $this->member = $member;
    }

    public function dashboard($startAt, $endAt)
    {
        $memberCount = $this->memberRepository->findWhere(['status' => 1])->count();
        $branchCount = $this->branchRepository->count();
        $totalChops = $this->chopRepository->sum('chops');
        $manualAddChops = $this->chopRecordRepository->findWhereIn('type', [ChopRecordRepository::ADD_CHOPS])->sum('chops');
        $earnChops = $this->chopRecordRepository->findWhereIn('type', [ChopRecordRepository::EARN_CHOPS])->sum('chops');
        $voidEarnChops = $this->chopRecordRepository->findWhereIn('type', [ChopRecordRepository::VOID_EARN_CHOPS])->sum('chops');
        $consumeChops = $this->chopRecordRepository->findWhereIn('type', [ChopRecordRepository::CONSUME_CHOPS])->sum('consume_chops');
        $voidConsumeChops = $this->chopRecordRepository->findWhereIn('type', [ChopRecordRepository::VOID_CONSUME_CHOPS])->sum('consume_chops');
        $totalAmount = $this->transactionRepository->findWhere(['status' => 1])->sum('amount');
        $ranks = $this->rankRepository->withCount('members')->all();

        return [
            'member_count' => (int)$memberCount,
            'branch_count' => (int)$branchCount,
            'total_chops' => (int)$totalChops,
            'manual_add_chops' => (int)$manualAddChops,
            'earn_chops' => (int)($earnChops + $voidEarnChops),
            'consume_chops' => (int)$consumeChops + $voidConsumeChops,
            'total_amount' => $totalAmount,
            'rank_members' => $ranks->map->only(['id', 'name', 'members_count'])
        ];
    }
}
