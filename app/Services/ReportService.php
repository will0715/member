<?php

namespace App\Services;

use App\Constants\PrepaidCardConstant;
use App\Constants\RecordConstant;
use App\Constants\ChopRecordConstant;
use App\Constants\TransactionConstant;
use App\Criterias\LimitOffsetCriteria;
use App\Criterias\RequestDateRangeCriteria;
use App\Criterias\OnlyTodayCriteria;
use App\Helpers\ReportHelper;
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
use Carbon\Carbon;

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
        $this->memberRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->branchRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->chopRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->prepaidCardRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->chopRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->transactionRepository->pushCriteria(new RequestDateRangeCriteria($request));

        $memberCount = $this->memberRepository->findValid()->count();
        $branchCount = $this->branchRepository->count();
        $prepaidCardTopup = $this->prepaidCardRecordRepository->findTopup()->sum('topup');
        $prepaidCardPayment = $this->prepaidCardRecordRepository->findPayment()->sum('payment');
        $voidPrepaidCardPayment = $this->prepaidCardRecordRepository->findVoidPayment()->sum('payment');

        $manualAddChops = $this->chopRecordRepository->findManualAddChops()->sum('chops');
        $earnChops = $this->chopRecordRepository->findEarnChops()->sum('chops');
        $voidEarnChops = $this->chopRecordRepository->findVoidEarnChops()->sum('chops');
        $consumeChops = $this->chopRecordRepository->findConsumeChops()->sum('consume_chops');
        $voidConsumeChops = $this->chopRecordRepository->findVoidConsumeChops()->sum('consume_chops');

        $totalTransactionCount = $this->transactionRepository->findValid()->count();
        $totalAmount = $this->transactionRepository->findValid()->sum('amount');

        return [
            'member_count' => (int)$memberCount,
            'branch_count' => (int)$branchCount,
            'manual_add_chops' => (int)$manualAddChops,
            'earn_chops' => (int)($earnChops + $voidEarnChops),
            'consume_chops' => (int)($consumeChops + $voidConsumeChops),
            'total_amount' => $totalAmount,
            'total_transaction_count' => $totalTransactionCount,
            'total_topup' => (int)$prepaidCardTopup,
            'total_paymnet' => (int)($prepaidCardPayment + $voidPrepaidCardPayment),
        ];
    }

    public function getTodayDashboardData()
    {
        $this->memberRepository->pushCriteria(new OnlyTodayCriteria());
        $this->branchRepository->pushCriteria(new OnlyTodayCriteria());
        $this->chopRepository->pushCriteria(new OnlyTodayCriteria());
        $this->prepaidCardRepository->pushCriteria(new OnlyTodayCriteria());
        $this->prepaidCardRecordRepository->pushCriteria(new OnlyTodayCriteria());
        $this->chopRecordRepository->pushCriteria(new OnlyTodayCriteria());
        $this->transactionRepository->pushCriteria(new OnlyTodayCriteria());
        
        $memberCount = $this->memberRepository->findValid()->count();
        $branchCount = $this->branchRepository->count();
        $prepaidCardTopup = $this->prepaidCardRecordRepository->findTopup()->sum('topup');
        $prepaidCardPayment = $this->prepaidCardRecordRepository->findPayment()->sum('payment');
        $voidPrepaidCardPayment = $this->prepaidCardRecordRepository->findVoidPayment()->sum('payment');

        $manualAddChops = $this->chopRecordRepository->findManualAddChops()->sum('chops');
        $earnChops = $this->chopRecordRepository->findEarnChops()->sum('chops');
        $voidEarnChops = $this->chopRecordRepository->findVoidEarnChops()->sum('chops');
        $consumeChops = $this->chopRecordRepository->findConsumeChops()->sum('consume_chops');
        $voidConsumeChops = $this->chopRecordRepository->findVoidConsumeChops()->sum('consume_chops');

        $totalTransactionCount = $this->transactionRepository->findValid()->count();
        $totalAmount = $this->transactionRepository->findValid()->sum('amount');

        return [
            'member_count' => (int)$memberCount,
            'branch_count' => (int)$branchCount,
            'manual_add_chops' => (int)$manualAddChops,
            'earn_chops' => (int)($earnChops + $voidEarnChops),
            'consume_chops' => (int)($consumeChops + $voidConsumeChops),
            'total_amount' => $totalAmount,
            'total_transaction_count' => $totalTransactionCount,
            'total_topup' => (int)$prepaidCardTopup,
            'total_paymnet' => (int)($prepaidCardPayment + $voidPrepaidCardPayment),
        ];
    }

    public function getMemberCountByDate($request)
    {
        $this->memberRepository->pushCriteria(new RequestDateRangeCriteria($request));

        $memberCount = $this->memberRepository->findValid()->countBy(ReportHelper::groupByCreatedDate());

        return $memberCount;
    }

    public function getBranchCountByDate($request)
    {
        $this->branchRepository->pushCriteria(new RequestDateRangeCriteria($request));

        $branchCount = $this->branchRepository->all()->countBy(ReportHelper::groupByCreatedDate());

        return $branchCount;
    }

    public function getEarnChopsByDate($request)
    {
        $this->chopRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));

        $chopRecord = $this->chopRecordRepository->findAllAddChops()->groupBy(ReportHelper::groupByCreatedDate())->map(ReportHelper::sumByColumn('chops'));

        return $chopRecord;
    }

    public function getConsumeChopsByDate($request)
    {
        $this->chopRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));

        $chopRecord = $this->chopRecordRepository->findAllConsumeChops()->groupBy(ReportHelper::groupByCreatedDate())->map(ReportHelper::sumByColumn('consume_chops'));

        return $chopRecord;
    }

    public function getTransactionCountByDate($request)
    {
        $this->transactionRepository->pushCriteria(new RequestDateRangeCriteria($request));

        $transactionCount = $this->transactionRepository->findValid()->countBy(ReportHelper::groupByCreatedDate());

        return $transactionCount;
    }

    public function getTransactionAmountByDate($request)
    {
        $this->transactionRepository->pushCriteria(new RequestDateRangeCriteria($request));

        $transactionAmount = $this->transactionRepository->findValid()->groupBy(ReportHelper::groupByCreatedDate())->map(ReportHelper::sumByColumn('amount'));

        return $transactionAmount;
    }

    public function getPrepaidCardTopupByDate($request)
    {
        $this->prepaidCardRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));

        $prepaidCardTopup = $this->prepaidCardRecordRepository->findTopup()->groupBy(ReportHelper::groupByCreatedDate())->map(ReportHelper::sumByColumn('topup'));

        return $prepaidCardTopup;
    }

    public function getPrepaidCardPaymentByDate($request)
    {
        $this->prepaidCardRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));

        $prepaidCardTopup = $this->prepaidCardRecordRepository->findPayment()->groupBy(ReportHelper::groupByCreatedDate())->map(ReportHelper::sumByColumn('payment'));

        return $prepaidCardTopup;
    }

    public function getRankMemberSummary(Request $request)
    {
        $this->memberRepository->pushCriteria(new RequestDateRangeCriteria($request));

        $member = $this->memberRepository->with(['rank'])->findValid();
        $memberCount = $member->countBy(function ($item) {
            return optional($item->rank)->name;
        });

        return $memberCount;
    }

    public function getMemberGenderTransactionAmountPercentageSummary(Request $request)
    {
        $this->transactionRepository->pushCriteria(new RequestDateRangeCriteria($request));

        return $this->transactionRepository->getWithMemberGender()->groupBy('member.gender')->map(function($item){
            return $item->sum('amount');
        });
    }

    public function getBranchChopConsumeChopSummary(Request $request)
    {
        $this->chopRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        
        $branchChopRecord = $this->chopRecordRepository->all();
        $branchChopConsumeChop = $branchChopRecord->groupBy('branch.name', function($item) {
            return $item->type;
        })->map(function($item) {
            return $item->groupBy('type')->map(function ($item) {
                return [
                    'chops' => $item->sum('chops'), 
                    'consume_chops' => $item->sum('consume_chops')
                ];
            });
        });
        $branchChopConsumeChop = $branchChopConsumeChop->map(function ($item) {
            return [
                'earn_chop' => Arr::get($item, 'EARN_CHOPS.chops', 0) - Arr::get($item, 'VOID_EARN_CHOPS.chops', 0),
                'consume_chop' => Arr::get($item, 'CONSUME_CHOPS.consume_chops', 0) - Arr::get($item, 'VOID_ECONSUME_CHOPS.consume_chops', 0)
            ];
        });

        return $branchChopConsumeChop;
    }

    public function getBranchRegisterMemberSummary(Request $request)
    {
        // 討論 是否加上時間
        $newBranchRegisterMember = $this->branchRepository->getWithNewRegisterMember();
        $oldBranchRegisterMember = $this->branchRepository->getWithOldRegisterMember();

        return [
            'new_branch_register_member' => $newBranchRegisterMember->map->only(['id', 'name', 'code', 'register_members_count']),
            'old_branch_register_member' => $oldBranchRegisterMember->map->only(['id', 'name', 'code', 'register_members_count']),
        ];
    }

    public function getPrepaidcardTopupRecords(Request $request)
    {
        $this->prepaidCardRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new RequestCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new LimitOffsetCriteria($request));
        $prepaidCardRecord = $this->prepaidCardRecordRepository->with(RecordConstant::BASIC_RELATIONS)->findTopup()->all();

        return $prepaidCardRecord;
    }

    public function getPrepaidcardPaymentRecords(Request $request)
    {
        $this->prepaidCardRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new RequestCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new LimitOffsetCriteria($request));
        $prepaidCardRecord = $this->prepaidCardRecordRepository
                            ->with(RecordConstant::BASIC_RELATIONS)
                            ->findPaymentAndVoidPayment()
                            ->all();

        return $prepaidCardRecord;
    }

    public function getAddChopsRecords(Request $request)
    {
        $this->chopRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->chopRecordRepository->pushCriteria(new RequestCriteria($request));
        $this->chopRecordRepository->pushCriteria(new LimitOffsetCriteria($request));
        $chopRecord = $this->chopRecordRepository
                        ->with(RecordConstant::BASIC_RELATIONS)
                        ->findAllAddChops()
                        ->all();

        return $chopRecord;
    }

    public function getConsumeChopsRecords(Request $request)
    {
        $this->chopRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->chopRecordRepository->pushCriteria(new RequestCriteria($request));
        $this->chopRecordRepository->pushCriteria(new LimitOffsetCriteria($request));
        $chopRecord = $this->chopRecordRepository
                        ->with(RecordConstant::BASIC_RELATIONS)
                        ->findAllConsumeChops()
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
