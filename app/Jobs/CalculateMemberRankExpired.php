<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Repositories\MemberRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\TransactionRepository;
use App\Services\CustomerService;
use App\Services\MemberService;
use App\Services\RankService;
use App\Models\RankExpiredSetting;
use Carbon\Carbon;
use Poyi\PGSchema\Facades\PGSchema;

class CalculateMemberRankExpired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->customerRepository = app(CustomerRepository::class);
        $this->memberService = app(MemberService::class);
        $this->memberRepository = app(MemberRepository::class);
        $this->rankService = app(RankService::class);
        $this->transactionRepository = app(TransactionRepository::class);

        $customers = $this->customerRepository->all();
        foreach ($customers as $customer) {
            $dbSchemaName = $customer->getSchema();
            Log::info('Calculating Rank Expired for Customer: ' . $customer->name);
            PGSchema::schema($dbSchemaName, 'pgsql');

            $rankExpiredSetting = $this->rankService->getRankExpiredSetting();

            if (!$rankExpiredSetting->is_active) {
                Log::info('Rank Expired Setting not active');
                return;
            }

            $calculateTimeUnit = $rankExpiredSetting->calculate_time_unit;
            $calculateTimeUnitValue = $rankExpiredSetting->calculate_time_value;
            switch ($calculateTimeUnit) {
                case RankExpiredSetting::CALCULATE_TIME_UNIT_DAY:
                    $calculateStartDate = Carbon::now()->subDays($calculateTimeUnitValue)->toDateString();
                    break;
                case RankExpiredSetting::CALCULATE_TIME_UNIT_MONTH:
                    $calculateStartDate = Carbon::now()->subMonths($calculateTimeUnitValue)->toDateString();
                    break;
                case RankExpiredSetting::CALCULATE_TIME_UNIT_YEAR:
                    $calculateStartDate = Carbon::now()->subYears($calculateTimeUnitValue)->toDateString();
                    break;
            }

            $baseRank = $this->rankService->getBasicRank();

            // 只計算加入時間在計算時間之前的會員
            $members = $this->memberRepository->scopeQuery(function($query) use ($calculateStartDate){
                return $query->where('rank_join_at', '<', $calculateStartDate)->orWhereNull('rank_join_at');
            })->all();
            foreach ($members as $member) {
                if ($member->rank->rank == 1) {
                    continue;
                }
                switch ($rankExpiredSetting->calculate_standard) {
                    case RankExpiredSetting::CALCULATE_STANDARD_AMOUNT:
                        $calculateTarget = $this->calculateTargetByAmount($member, $calculateStartDate);
                        break;
                    case RankExpiredSetting::CALCULATE_STANDARD_TIMES:
                        $calculateTarget = $this->calculateTargetByTimes($member, $calculateStartDate);
                        break;
                }
                if ($calculateTarget < $rankExpiredSetting->calculate_standard_value) {
                    $this->memberService->updateMemberRank($baseRank->id, $member->id);
                    Log::info('Member: ' . $member->phone . ' Reset to Basic Rank');
                }
            }
        }
    }

    private function calculateTargetByAmount($member, $calculateStartDate)
    {
        $transactions = $this->transactionRepository->findWhere([
            'status' => 1,
            'member_id' => $member->id,
            ['transaction_time', '>=', $calculateStartDate]
        ]);
        return $transactions->sum('amount');
    }

    private function calculateTargetByTimes($member, $calculateStartDate)
    {
        $transactions = $this->transactionRepository->findWhere([
            'status' => 1,
            'member_id' => $member->id,
            ['transaction_time', '>=', $calculateStartDate]
        ]);
        return $transactions->count();
    }
}
