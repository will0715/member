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
use App\Events\MemberRegistered;
use Cache;
use DB;

class ChopService
{
    /** @var  TransactionRepository */
    private $transactionRepository;
    /** @var  ChopRepository */
    private $chopRepository;
    private $customer;
    private $member;
    private $branch;

    public function __construct($customer = '')
    {
        $this->chopRepository = app(ChopRepository::class);
        $this->chopExpiredSettingRepository = app(ChopExpiredSettingRepository::class);
        $this->chopRecordRepository = app(ChopRecordRepository::class);
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->customer = $customer;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    public function setMember($member)
    {
        $this->member = $member;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    public function manualAddChops($chops)
    {
        $expiredSetting = $this->getChopsExpiredSetting();
        $member = $this->member;
        $branch = $this->branch;

        $record = null;
        DB::transaction(function () use ($member, $branch, $expiredSetting, $chops) {
            // add chop
            $this->chopRepository->addChops($member, $branch, $chops);
            
            // add chop record
            $record = $this->chopRecordRepository->newManualChopRecord([
                'member' => $member,
                'branch' => $branch,
                'chops' => $chops
            ]);
        }, 5);

        return $record;
    }

    public function consumeChops($chops)
    {
        $expiredSetting = $this->getChopsExpiredSetting();
        $member = $this->member;
        $branch = $this->branch;
        
        $totalChops = $this->getTotalChops($member, $branch);
        if ($totalChops < $chops) {
            throw new ChopsNotEnoughException();
        }

        $record = null;
        DB::beginTransaction();
        try {
            // consume chop
            $consumeChops = $this->chopRepository->consumeChops($member, $branch, $chops);

            // add chop record
            $record = $this->chopRecordRepository->newConsumeChopRecord([
                'member' => $member,
                'branch' => $branch,
                'consumeChops' => $chops,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $record;
    }

    public function voidConsumeChops($id)
    {
        $record = $this->chopRecordRepository->findWithoutFail($id);
        if (!$record) {
            throw new \Exception('Can\'t not find the consume record');
        }
        if (!empty($record->voidRecord)) {
            throw new AlreadyVoidedException($record);
        }
        $member = $record->member;
        $branch = $record->branch;
        $voidRecord = null;
        DB::transaction(function () use ($member, $branch, $record, $id) {

            $chops = -1 * $record->consume_chops;

            // add chops
            $this->chopRepository->addChops($member, $branch, $chops);

            // add void record
            $voidRecord = $this->chopRecordRepository->voidRecord($id);
        }, 5);

        return $voidRecord;
    }

    public function getTotalChops($member, $branch)
    {
        $isBranchIndependent = $branch->isIndependent();
        if ($isBranchIndependent) {
            $chop = $this->chopRepository->getBranchChops($member, $branch);
            $totalChops = $chop->chops;
        } else {
            $unindependentBranches = $this->branchRepository->getUnindependentBranches();
            $chops = $this->chopRepository->getMemberBranchesChops($member, $unindependentBranches->pluck('id'));
            $totalChops = $chops->sum('chops');
        }
        return $totalChops;
    }

    private function getChopsExpiredSetting()
    {
        return $this->chopExpiredSettingRepository->getChopsExpiredSetting();
    }
}
