<?php

namespace App\Services;

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

    public function findChopRecord($id)
    {
        $chopRecord = $this->chopRecordRepository->findWithoutFail($id);
        if (!$chopRecord) {
            throw new ResourceNotFoundException('Chop Record Not Found');
        }
        return $chopRecord;
    }

    public function listChops($request)
    {
        $this->chopRepository->pushCriteria(new RequestCriteria($request));
        $this->chopRepository->pushCriteria(new LimitOffsetCriteria($request));
        $chops = $this->chopRepository->all();

        return $chops;
    }

    public function listChopsByMemberId($memberId)
    {
        $chops = $this->chopRepository->getMemberChops($memberId);

        return $chops;
    }

    public function listRecords($request)
    {
        $this->chopRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->chopRecordRepository->pushCriteria(new RequestCriteria($request));
        $this->chopRecordRepository->pushCriteria(new LimitOffsetCriteria($request));
        $records = $this->chopRecordRepository->all();

        return $records;
    }

    public function findChopsRecordsByMember($memberId)
    {
        return $this->chopRecordRepository->orderBy('created_at', 'desc')->findWhere(['member_id' => $memberId]);
    }

    public function findChopsRecordsByTransactionNo($transactionNo)
    {
        return $this->chopRecordRepository->findWhere(['transaction_no' => $transactionNo]);
    }

    public function manualAddChops($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $addChops = $attributes['chops'];
        $transactionNo = Arr::get($attributes, 'transaction_no');
        $remark = Arr::get($attributes, 'remark');
        $expiredSetting = $this->getChopsExpiredSetting();

        // add chop
        $chop = $this->chopRepository->getBranchChops($memberId, $branchId);
        if (!$chop) {
            $newChop = $this->chopRepository->create([
                'member_id' => $memberId,
                'branch_id' => $branchId,
                'chops' => $addChops,
                'expired_at' => Carbon::now()->add($expiredSetting->expired_date, 'days')
            ]);
        } else {
            $newChop = $this->chopRepository->update([
                'chops' => $chop->chops + $addChops,
                'expired_at' => Carbon::now()->add($expiredSetting->expired_date, 'days')
            ], $chop->id);
        }

        // add add chop record
        $record = $this->chopRecordRepository->newManualChopRecord([
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'chops' => $addChops,
            'transaction_no' => $transactionNo,
            'remark' => $remark
        ]);

        return $record;
    }

    public function earnChops($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $addChops = $attributes['chops'];
        $transactionNo = Arr::get($attributes, 'transaction_no');
        $earnChopsRuleId = Arr::get($attributes, 'earn_chop_rule_id');
        $remark = Arr::get($attributes, 'remark');
        $expiredSetting = $this->getChopsExpiredSetting();

        // add chop
        $chop = $this->chopRepository->getBranchChops($memberId, $branchId);
        if (!$chop) {
            // TODO: if it is expired have to move to expired chops
            $newChop = $this->chopRepository->updateOrCreate([
                'member_id' => $memberId,
                'branch_id' => $branchId,
            ], [
                'chops' => $addChops,
                'expired_at' => Carbon::now()->add($expiredSetting->expired_date, 'days')
            ]);
        } else {
            $newChop = $this->chopRepository->update([
                'chops' => $chop->chops + $addChops,
                'expired_at' => Carbon::now()->add($expiredSetting->expired_date, 'days')
            ], $chop->id);
        }

        // add add chop record
        $record = $this->chopRecordRepository->newEarnChopRecord([
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'chops' => $addChops,
            'rule_id' => $earnChopsRuleId,
            'transaction_no' => $transactionNo,
            'remark' => $remark
        ]);

        return $record;
    }

    public function consumeChops($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $consumeChops = $attributes['chops'];
        $ruleId = $attributes['rule_id'];
        $transactionNo = Arr::get($attributes, 'transaction_no');
        $remark = Arr::get($attributes, 'remark');
        $expiredSetting = $this->getChopsExpiredSetting();
        $totalChops = $this->getCanConsumeChops($memberId, $branchId);

        if ($totalChops < $consumeChops) {
            throw new ChopsNotEnoughException();
        }
        // consume chop
        $chop = $this->chopRepository->getBranchChops($memberId, $branchId);
        if (!$chop) {
            $newChop = $this->chopRepository->create([
                'member_id' => $memberId,
                'branch_id' => $branchId,
                'chops' => $consumeChops * -1,
                'expired_at' => Carbon::now()->add($expiredSetting->expired_date, 'days')
            ]);
        } else {
            $newChop = $this->chopRepository->update([
                'chops' => $chop->chops - $consumeChops
            ], $chop->id);
        }

        // add consume chop record
        $record = $this->chopRecordRepository->newConsumeChopRecord([
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'rule_id' => $ruleId,
            'consume_chops' => $consumeChops,
            'transaction_no' => $transactionNo,
            'remark' => $remark
        ]);

        return $record;
    }

    public function voidEarnChops($id, $attributes = [])
    {
        $remark = Arr::get($attributes, 'remark');
        $record = $this->chopRecordRepository->find($id);
        if ($record->type != ChopRecordConstant::CHOP_RECORD_EARN_CHOPS) {
            throw new CannotVoidException();
        }
        if (!empty($record->voidRecord)) {
            throw new AlreadyVoidedException();
        }
        $memberId = $record->member_id;
        $branchId = $record->branch_id;

        // add chop
        $chop = $this->chopRepository->getBranchChops($memberId, $branchId);
        $voidEarnChops = $record->chops;

        // add chops
        $newChop = $this->chopRepository->update([
            'chops' => $chop->chops - $voidEarnChops
        ], $chop->id);

        // add void record
        $voidRecord = $this->chopRecordRepository->voidEarnChopRecord([
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'chops' => -1 * $voidEarnChops,
            'void_id' => $record->id,
            'remark' => $remark
        ]);

        return $voidRecord;
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
        $memberId = $record->member_id;
        $branchId = $record->branch_id;

        // add chop
        $chop = $this->chopRepository->getBranchChops($memberId, $branchId);
        $voidConsumeChops = $record->consume_chops;

        // add chops
        $newChop = $this->chopRepository->update([
            'chops' => $chop->chops + $voidConsumeChops
        ], $chop->id);

        // add void record
        $voidRecord = $this->chopRecordRepository->voidConsumeChopRecord([
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'consume_chops' => -1 * $voidConsumeChops,
            'void_id' => $record->id,
            'remark' => $remark
        ]);

        return $voidRecord;
    }

    /*
        取得使用者在這家店所有可以看到點數
    */
    public function getCanConsumeChops($memberId, $branchId)
    {
        $branch = $this->branchRepository->find($branchId);
        $isBranchIndependent = $branch->isIndependent();
        $isDisableConsumeOtherBranchChop = $branch->isDisableConsumeOtherBranchChop();
        if ($isBranchIndependent) {
            $chop = $this->chopRepository->getBranchChops($memberId, $branchId);
            $totalChops = $chop ? $chop->chops : 0;
        } else if ($isDisableConsumeOtherBranchChop) {
            $currentBranchChop = $this->chopRepository->getBranchChops($memberId, $branchId);
            $memberTotalChops = $this->chopRepository->getMemberChops($memberId);
            $totalChops = min($currentBranchChop ? $currentBranchChop->chops : 0, $memberTotalChops->sum('chops'));
        } else {
            $unindependentBranches = $this->branchRepository->getUnindependentBranches();
            $chops = $this->chopRepository->getMemberBranchesChops($memberId, $unindependentBranches->pluck('id'));
            $totalChops = (int) $chops->sum('chops');
        }
        return $totalChops;
    }

    private function getChopsExpiredSetting()
    {
        return $this->chopExpiredSettingRepository->getChopsExpiredSetting();
    }
}
