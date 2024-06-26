<?php

namespace App\ServiceManagers\V2;

use App\Exceptions\ResourceNotFoundException;
use App\Services\MemberService;
use App\Services\BranchService;
use App\Services\v2\ChopService;
use App\Constants\ChopRecordConstant;
use Arr;

class MemberChopServiceManager
{

    private $memberService;
    private $branchService;
    private $chopService;

    public function __construct()
    {
        $this->memberService = app(MemberService::class);
        $this->branchService = app(branchService::class);
        $this->chopService = app(ChopService::class);
    }

    public function consumeChops($attributes)
    {
        $phone = $attributes['phone'];
        $branchId = $attributes['branch_id'];
        $chops = $attributes['chops'];
        $ruleId = Arr::get($attributes, 'rule_id', null);
        $remark = Arr::get($attributes, 'remark');
        $transactionNo = Arr::get($attributes, 'transaction_no');

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        // search branch
        $branch = $this->branchService->findBranchByCode($branchId);

        $records = $this->chopService->consumeChops([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'rule_id' => $ruleId,
            'chops' => $chops,
            'transaction_no' => $transactionNo,
            'remark' => $remark
        ]);
        collect($records)->map(function ($record) {
            $record->load(ChopRecordConstant::BASIC_RELATIONS);
        });

        // summary record
        $consumeSummaryRecord = [
            'id' => Arr::last($records)->id,
            'type' => Arr::last($records)->type,
            'chops' => collect($records)->sum('chops'),
            'consume_chops' => collect($records)->sum('consume_chops'),
            'created_at' => Arr::last($records)->created_at,
            'transaction_no' => Arr::last($records)->transaction_no,
            'remark' => Arr::last($records)->remark,
            'records' => $records
        ];

        return $consumeSummaryRecord;
    }

    public function voidConsumeChops($id, $attributes)
    {
        $records = $this->chopService->voidConsumeChops($id, $attributes);
        collect($records)->map(function ($record) {
            $record->load(ChopRecordConstant::BASIC_RELATIONS);
        });

        // summary record
        $consumeSummaryRecord = [
            'id' => Arr::last($records)->id,
            'type' => Arr::last($records)->type,
            'chops' => collect($records)->sum('chops'),
            'consume_chops' => collect($records)->sum('consume_chops'),
            'created_at' => Arr::last($records)->created_at,
            'transaction_no' => Arr::last($records)->transaction_no,
            'remark' => Arr::last($records)->remark,
            'records' => $records
        ];

        return $consumeSummaryRecord;
    }
}
