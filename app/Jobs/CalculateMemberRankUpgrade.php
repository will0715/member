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
use App\Repositories\ChopRecordRepository;
use App\Services\CustomerService;
use App\Services\MemberService;
use App\Services\RankService;
use App\Models\RankUpgradeSetting;
use Carbon\Carbon;
use Poyi\PGSchema\Facades\PGSchema;

class CalculateMemberRankUpgrade implements ShouldQueue
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
        $this->chopRecordRepository = app(ChopRecordRepository::class);

        $customers = $this->customerRepository->all();
        foreach ($customers as $customer) {
            $dbSchemaName = $customer->getSchema();
            Log::info('Calculating Rank Upgrade for Customer: ' . $customer->name);
            PGSchema::schema($dbSchemaName, 'pgsql');

            $rankUpgradeSettings = $this->rankService->listRankUpgradeSettings();

            $members = $this->memberRepository->all();
            foreach ($members as $member) {
                $currentRank = $member->rank->rank;
                $rankUpgradeSetting = $rankUpgradeSettings->firstWhere('rank.rank', $currentRank + 1);
                Log::info('Member: ' . $member->phone . ' Current Rank: ' . $currentRank);
                if (!$rankUpgradeSetting) {
                    Log::info('No Next Rank Upgrade Setting');
                    continue;
                }
                if (!$rankUpgradeSetting->is_active) {
                    Log::info($currentRank + 1 . 'Rank Upgrade Setting not active');
                    continue;
                }
                $nextRank = $rankUpgradeSetting->rank;
                $calculateTimeUnit = $rankUpgradeSetting->calculate_time_unit;
                $calculateTimeUnitValue = $rankUpgradeSetting->calculate_time_value;
                switch ($calculateTimeUnit) {
                    case RankUpgradeSetting::CALCULATE_TIME_UNIT_DAY:
                        $calculateStartDate = Carbon::now()->subDays($calculateTimeUnitValue)->toDateString();
                        break;
                    case RankUpgradeSetting::CALCULATE_TIME_UNIT_MONTH:
                        $calculateStartDate = Carbon::now()->subMonths($calculateTimeUnitValue)->toDateString();
                        break;
                    case RankUpgradeSetting::CALCULATE_TIME_UNIT_YEAR:
                        $calculateStartDate = Carbon::now()->subYears($calculateTimeUnitValue)->toDateString();
                        break;
                }
                $calculateStartDate = max($calculateStartDate, $member->rank_join_at);

                switch ($rankUpgradeSetting->calculate_standard) {
                    case RankUpgradeSetting::CALCULATE_STANDARD_AMOUNT:
                        $calculateTarget = $this->calculateTargetByAmount($member, $calculateStartDate);
                        break;
                    case RankUpgradeSetting::CALCULATE_STANDARD_TIMES:
                        $calculateTarget = $this->calculateTargetByTimes($member, $calculateStartDate);
                        break;
                    case RankUpgradeSetting::CALCULATE_STANDARD_CHOPS:
                        $calculateTarget = $this->calculateTargetByChop($member, $calculateStartDate);
                        break;
                }
                Log::info('Member: ' . $member->phone . ' Calculate Target: ' . $calculateTarget . ' Standard Value: ' . $rankUpgradeSetting->calculate_standard_value);
                if ($calculateTarget >= $rankUpgradeSetting->calculate_standard_value) {
                    $this->memberService->updateMemberRank($nextRank->id, $member->id);
                    Log::info('Member: ' . $member->phone . ' Upgarde to Rank: ' . $nextRank->rank);
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

    private function calculateTargetByChop($member, $calculateStartDate)
    {
        $chopRecords = $this->chopRecordRepository->findWhere([
            'member_id' => $member->id,
            ['updated_at', '>=', $calculateStartDate]
        ]);
        $totalChop = $chopRecords->sum('chops') - $chopRecords->sum('consume_chops');
        return $totalChop;
    }
}
