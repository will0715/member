<?php

namespace App\Services;

use App\Constants\PrepaidCardConstant;
use App\Constants\RecordConstant;
use App\Constants\ChopRecordConstant;
use App\Criterias\LimitOffsetCriteria;
use App\Constants\TransactionConstant;
use App\Criterias\RequestDateRangeCriteria;
use App\Repositories\ChopExpiredSettingRepository;
use App\Repositories\ChopRecordRepository;
use App\Repositories\ChopRepository;
use App\Repositories\PrepaidCardRepository;
use App\Repositories\PrepaidCardRecordRepository;
use App\Repositories\RankRepository;
use App\Repositories\MemberRepository;
use App\Repositories\BranchRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Arr;

class ReportService
{
    /** @var  TransactionRepository */
    private $transactionRepository;
    /** @var  ChopRepository */
    private $chopRepository;

    public function __construct()
    {
        $this->prepaidCardRepository = app(PrepaidCardRepository::class);
        $this->prepaidCardRecordRepository = app(PrepaidCardRecordRepository::class);
        $this->chopRepository = app(ChopRepository::class);
        $this->chopRecordRepository = app(ChopRecordRepository::class);
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->transactionRepository = app(TransactionRepository::class);
        $this->rankRepository = app(RankRepository::class);
    }

    public function dashboard(Request $request)
    {
        $startAt = $request->get('start');
        $endAt = $request->get('end');

        $memberCount = $this->memberRepository->findWhere(['status' => 1])->count();
        $branchCount = $this->branchRepository->count();
        $totalChops = $this->chopRepository->sum('chops');
        $totalBalance = $this->prepaidCardRepository->sum('balance');
        $prepaidCardTopup = $this->prepaidCardRecordRepository->findWhereIn('type', [PrepaidCardConstant::PREPAIDCARD_TYPE_TOPUP])->sum('topup');
        $prepaidCardPayment = $this->prepaidCardRecordRepository->findWhereIn('type', [PrepaidCardConstant::PREPAIDCARD_TYPE_PAYMENT])->sum('payment');
        $voidPrepaidCardPayment = $this->prepaidCardRecordRepository->findWhereIn('type', [PrepaidCardConstant::PREPAIDCARD_TYPE_VOID_PAYMENT])->sum('payment');

        $manualAddChops = $this->chopRecordRepository->findWhereIn('type', [ChopRecordConstant::CHOP_RECORD_ADD_CHOPS])->sum('chops');
        $earnChops = $this->chopRecordRepository->findWhereIn('type', [ChopRecordConstant::CHOP_RECORD_EARN_CHOPS])->sum('chops');
        $voidEarnChops = $this->chopRecordRepository->findWhereIn('type', [ChopRecordConstant::CHOP_RECORD_VOID_EARN_CHOPS])->sum('chops');
        $consumeChops = $this->chopRecordRepository->findWhereIn('type', [ChopRecordConstant::CHOP_RECORD_CONSUME_CHOPS])->sum('consume_chops');
        $voidConsumeChops = $this->chopRecordRepository->findWhereIn('type', [ChopRecordConstant::CHOP_RECORD_VOID_CONSUME_CHOPS])->sum('consume_chops');

        $totalAmount = $this->transactionRepository->findWhere(['status' => 1])->sum('amount');
        $ranks = $this->rankRepository->withCount('members')->all();

        return [
            'member_count' => (int)$memberCount,
            'branch_count' => (int)$branchCount,
            'total_chops' => (int)$totalChops,
            'manual_add_chops' => (int)$manualAddChops,
            'earn_chops' => (int)($earnChops + $voidEarnChops),
            'consume_chops' => (int)($consumeChops + $voidConsumeChops),
            'total_amount' => $totalAmount,
            'rank_members' => $ranks->map->only(['id', 'name', 'members_count']),
            
            'total_balance' => (int)$totalBalance,
            'total_topup' => (int)$prepaidCardTopup,
            'total_paymnet' => (int)($prepaidCardPayment + $voidPrepaidCardPayment),
        ];
    }

    public function getPrepaidcardTopupRecords(Request $request)
    {
        $this->prepaidCardRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new RequestCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new LimitOffsetCriteria($request));
        $prepaidCardRecord = $this->prepaidCardRecordRepository->whereIn('type', [PrepaidCardConstant::PREPAIDCARD_TYPE_TOPUP])->with(RecordConstant::BASIC_RELATIONS)->all();

        return $prepaidCardRecord;
    }

    public function getPrepaidcardPaymentRecords(Request $request)
    {
        $this->prepaidCardRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new RequestCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new LimitOffsetCriteria($request));
        $prepaidCardRecord = $this->prepaidCardRecordRepository
                            ->whereIn('type', [PrepaidCardConstant::PREPAIDCARD_TYPE_PAYMENT])
                            ->with(RecordConstant::BASIC_RELATIONS)
                            ->all();

        return $prepaidCardRecord;
    }

    public function getAddChopsRecords(Request $request)
    {
        $this->chopRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->chopRecordRepository->pushCriteria(new RequestCriteria($request));
        $this->chopRecordRepository->pushCriteria(new LimitOffsetCriteria($request));
        $chopRecord = $this->chopRecordRepository
                        ->whereIn('type', [
                            ChopRecordConstant::CHOP_RECORD_ADD_CHOPS, 
                            ChopRecordConstant::CHOP_RECORD_EARN_CHOPS,
                            ChopRecordConstant::CHOP_RECORD_VOID_EARN_CHOPS
                        ])
                        ->with(RecordConstant::BASIC_RELATIONS)
                        ->all();

        return $chopRecord;
    }

    public function getConsumeChopsRecords(Request $request)
    {
        $this->chopRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->chopRecordRepository->pushCriteria(new RequestCriteria($request));
        $this->chopRecordRepository->pushCriteria(new LimitOffsetCriteria($request));
        $chopRecord = $this->chopRecordRepository
                        ->whereIn('type', [
                            ChopRecordConstant::CHOP_RECORD_CONSUME_CHOPS, 
                            ChopRecordConstant::CHOP_RECORD_VOID_CONSUME_CHOPS
                        ])
                        ->with(RecordConstant::BASIC_RELATIONS)
                        ->all();

        return $chopRecord;
    }

    public function getTransactionRecords(Request $request)
    {
        $this->transactionRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->transactionRepository->pushCriteria(new RequestCriteria($request));
        $this->transactionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $transactions = $this->transactionRepository->with(TransactionConstant::BASIC_RELATIONS)->all();

        return $transactions;
    }

    public function getMemberRegisterBranchDetail(Request $request)
    {
        $startAt = Arr::get($request, 'start', null);
        $endAt = Arr::get($request, 'end', null);

        $this->memberRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->memberRepository->pushCriteria(new RequestCriteria($request));
        $this->memberRepository->pushCriteria(new LimitOffsetCriteria($request));

        $members = $this->memberRepository->with('registerBranch')->all();

        return $members;
    }

    public function getMemberRegisterBranchStatistics(Request $request)
    {
        $startAt = Arr::get($request, 'start', null);
        $endAt = Arr::get($request, 'end', null);

        $this->branchRepository->pushCriteria(new RequestCriteria($request));
        $this->branchRepository->pushCriteria(new LimitOffsetCriteria($request));

        $branch = $this->branchRepository->withRegisterMemberCount($startAt, $endAt)->get();

        return $branch;
    }
}
