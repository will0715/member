<?php

namespace App\Services\v2;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Constants\ChopRecordConstant;
use App\Criterias\LimitOffsetCriteria;
use App\Criterias\RequestDateRangeCriteria;
use App\Exceptions\CannotVoidException;
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
use App\Events\MemberRegistered;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Cache;
use DB;
use Arr;

class ChopService
{
    /** @var  TransactionRepository */
    private $transactionRepository;
    /** @var  ChopRepository */
    private $chopRepository;

    public function __construct()
    {
        $this->chopRepository = app(ChopRepository::class);
        $this->chopExpiredSettingRepository = app(ChopExpiredSettingRepository::class);
        $this->chopRecordRepository = app(ChopRecordRepository::class);
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
    }

    public function consumeChops($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $consumeChops = $attributes['chops'];
        $ruleId = $attributes['rule_id'];
        $transactionNo = Arr::get($attributes, 'transaction_no') ?: (string) Str::uuid();
        $remark = Arr::get($attributes, 'remark');
        $expiredSetting = $this->getChopsExpiredSetting();
        $totalChops = $this->getCanConsumeChops($memberId, $branchId);

        if ($totalChops < $consumeChops) {
            throw new ChopsNotEnoughException();
        }
        $remainConsumeChops = $consumeChops;
        $consumeBranches = $this->getBranchCanConsumeBranches($branchId);

        // 依序扣除分店點數，直到要兌換的點數扣完為止
        $earnChopsRecords = [];
        foreach ($consumeBranches as $branch) {
            $currentConsumeBranchId = $branch->id;
            $chop = $this->chopRepository->getBranchChops($memberId, $currentConsumeBranchId);
            if (!$chop) {
                continue;
            }
            if ($chop->chops <= 0) {
                continue;
            }
            $branchConsumeChops = min($chop->chops, $remainConsumeChops);
            $remainConsumeChops -= $branchConsumeChops;
            $chop = $this->chopRepository->update([
                'chops' => $chop->chops - $branchConsumeChops
            ], $chop->id);

            // add consume chop record
            $record = $this->chopRecordRepository->newConsumeChopRecord([
                'member_id' => $memberId,
                'branch_id' => $currentConsumeBranchId,
                'rule_id' => $ruleId,
                'consume_chops' => $branchConsumeChops,
                'transaction_no' => $transactionNo,
                'remark' => $remark
            ]);
            $earnChopsRecords[] = $record;

            $remainConsumeChops -= $chop->chops;
            if ($remainConsumeChops <= 0) {
                break;
            }
        }

        return $earnChopsRecords;
    }

    public function voidConsumeChops($id, $attributes = [])
    {
        $remark = Arr::get($attributes, 'remark');
        $record = $this->chopRecordRepository->find($id);
        if ($record->type != ChopRecordConstant::CHOP_RECORD_CONSUME_CHOPS) {
            throw new CannotVoidException();
        }
        if (!empty($record->voidRecord)) {
            throw new AlreadyVoidedException();
        }

        // 取得所有相同交易編號的消費紀錄
        if (!$record->transaction_no) {
            $needVoidRecord = [$record];
        } else {
            $needVoidRecords = $this->chopRecordRepository->findWhere([
                'type' => ChopRecordConstant::CHOP_RECORD_CONSUME_CHOPS,
                'transaction_no' => $record->transaction_no
            ]);
        }

        $voidRecords = [];
        foreach ($needVoidRecords as $needVoidRecord) {
            if (!empty($needVoidRecord->voidRecord)) {
                continue;
            }

            $memberId = $needVoidRecord->member_id;
            $branchId = $needVoidRecord->branch_id;

            $chop = $this->chopRepository->getBranchChops($memberId, $branchId);
            $voidConsumeChops = $needVoidRecord->consume_chops;

            // add chops
            $newChop = $this->chopRepository->update([
                'chops' => $chop->chops + $voidConsumeChops
            ], $chop->id);

            // add void record
            $voidRecord = $this->chopRecordRepository->voidConsumeChopRecord([
                'member_id' => $memberId,
                'branch_id' => $branchId,
                'consume_chops' => -1 * $voidConsumeChops,
                'void_id' => $needVoidRecord->id,
                'remark' => $remark
            ]);
            $voidRecords[] = $voidRecord;
        }

        return $voidRecords;
    }

    /*
        取得使用者在這家店所有可以看到點數
    */
    public function getCanConsumeChops($memberId, $branchId)
    {
        $branch = $this->branchRepository->find($branchId);
        $isBranchIndependent = $branch->isIndependent();
        $isDisableConsumeOtherBranchChop = $branch->isDisableConsumeOtherBranchChop();
        if ($isBranchIndependent || $isDisableConsumeOtherBranchChop) {
            $chop = $this->chopRepository->getBranchChops($memberId, $branchId);
            $totalChops = $chop ? $chop->chops : 0;
        } else {
            $unindependentBranches = $this->branchRepository->getUnindependentBranches();
            $chops = $this->chopRepository->getMemberBranchesChops($memberId, $unindependentBranches->pluck('id'));
            $totalChops = (int) $chops->sum('chops');
        }
        return $totalChops;
    }

    private function getBranchCanConsumeBranches($branchId)
    {
        $branch = $this->branchRepository->find($branchId);
        $isBranchIndependent = $branch->isIndependent();
        $isDisableConsumeOtherBranchChop = $branch->isDisableConsumeOtherBranchChop();

        if ($isBranchIndependent || $isDisableConsumeOtherBranchChop) {
            return [$branch];
        } else {
            // 取得所有非獨立分店 (is_disable_consume_other_branch_chop 的依然要算)
            $consumeBranches = $this->branchRepository->orderBy('code', 'asc')->findByField('is_independent', false);
            // 將目前的分店排在第一個
            $sortedBranches = $consumeBranches->sortBy(function ($branch) use ($branchId) {
                return $branch->id === $branchId ? 0 : 1;
            });
            return $sortedBranches;
        }
        return [];
    }

    private function getChopsExpiredSetting()
    {
        return $this->chopExpiredSettingRepository->getChopsExpiredSetting();
    }
}
