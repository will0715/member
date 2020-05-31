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
        return $this->chopRecordRepository->findWhere(['member_id' => $memberId]);
    }

    public function manualAddChops($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $addChops = $attributes['chops'];
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
            'chops' => $addChops
        ]);

        return $record;
    }

    public function earnChops($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $transactionId = $attributes['transaction_id'];
        $earnChopsRuleId = $attributes['earn_chop_rule_id'];
        $addChops = $attributes['chops'];
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
        $record = $this->chopRecordRepository->newEarnChopRecord([
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'transaction_id' => $transactionId,
            'chops' => $addChops,
            'rule_id' => $earnChopsRuleId
        ]);

        return $record;
    }

    public function consumeChops($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $consumeChops = $attributes['chops'];
        $ruleId = $attributes['rule_id'];
        
        $totalChops = $this->getTotalChops($memberId, $branchId);
        if ($totalChops < $consumeChops) {
            throw new ChopsNotEnoughException('Chops not enough');
        }
        
        // consume chop
        $chop = $this->chopRepository->getBranchChops($memberId, $branchId);
        if ($chop) {
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
        ]);

        return $record;
    }

    public function voidEarnChops($id, $attributes = [])
    {
        $record = $this->chopRecordRepository->find($id);
        if ($record->type != ChopRecordConstant::CHOP_RECORD_EARN_CHOPS) {
            throw new CannotVoidException('Can\'t not void not earn record');
        }
        if (!empty($record->voidRecord)) {
            throw new AlreadyVoidedException('consume already voided', $record);
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
            'void_id' => $record->id
        ]);

        return $voidRecord;
    }

    public function voidConsumeChops($id, $attributes = [])
    {
        $record = $this->chopRecordRepository->find($id);
        if ($record->type != ChopRecordConstant::CHOP_RECORD_CONSUME_CHOPS) {
            throw new CannotVoidException('Can\'t not void not consume record');
        }
        if (!empty($record->voidRecord)) {
            throw new AlreadyVoidedException('consume already voided', $record);
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
            'void_id' => $record->id
        ]);

        return $voidRecord;
    }

    public function getTotalChops($memberId, $branchId)
    {
        $branch = $this->branchRepository->find($branchId);
        $isBranchIndependent = $branch->isIndependent();
        if ($isBranchIndependent) {
            $chop = $this->chopRepository->getBranchChops($memberId, $branchId);
            $totalChops = $chop->chops;
        } else {
            $unindependentBranches = $this->branchRepository->getUnindependentBranches();
            $chops = $this->chopRepository->getMemberBranchesChops($memberId, $unindependentBranches->pluck('id'));
            $totalChops = $chops->sum('chops');
        }
        return $totalChops;
    }

    private function getChopsExpiredSetting()
    {
        return $this->chopExpiredSettingRepository->getChopsExpiredSetting();
    }
}
