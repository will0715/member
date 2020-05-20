<?php

namespace App\Services;

use App\Exceptions\PrepaidCardsNotEnoughException;
use App\Exceptions\AlreadyVoidedException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Member;
use App\Repositories\PrepaidCardRecordRepository;
use App\Repositories\PrepaidCardRepository;
use Illuminate\Http\Request;
use Arr;
use DB;

class PrepaidCardService
{
    /** @var  PrepaidCardRepository */
    private $prepaidCardRepository;
    private $member;
    private $branch;

    public function __construct()
    {
        $this->prepaidCardRepository = app(PrepaidCardRepository::class);
        $this->prepaidCardRecordRepository = app(PrepaidCardRecordRepository::class);
    }

    public function topup($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $topup = $attributes['topup'];
        $remark = $attributes['remark'];

        // need lock row for update
        $memberPrepaidCard = $this->prepaidCardRepository->getByMemberIdWithLock($memberId);

        if (!$memberPrepaidCard) {
            $newMemberPrepaidCard = $this->prepaidCardRepository->create([
                'member_id' => $memberId,
                'balance' => $topup
            ]);
        } else {
            $newMemberPrepaidCard = $this->prepaidCardRepository->update([
                'balance' => $memberPrepaidCard->balance + $topup
            ], $memberPrepaidCard->id);
        }

        $record = $this->prepaidCardRecordRepository->newTopUpRecord([
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'topup' => $topup,
            'remark' => $remark
        ]);

        return $record;
    }

    public function payment($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $payment = $attributes['payment'];
        $remark = $attributes['remark'];
        
        // need lock row for update
        $memberPrepaidCard = $this->prepaidCardRepository->getByMemberIdWithLock($memberId);
        $newBalance = $memberPrepaidCard ? $memberPrepaidCard->balance - $payment : -1 * $payment;

        if ($newBalance < 0) {
            throw new PrepaidCardsNotEnoughException('balance not enough');
        }

        $newMemberPrepaidCard = $this->prepaidCardRepository->update([
            'balance' => $newBalance
        ], $memberPrepaidCard->id);

        $record = $this->prepaidCardRecordRepository->newPaymentRecord([
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'payment' => $payment,
            'remark' => $remark
        ]);

        return $record;
    }

    public function voidPayment($id, $attributes = [])
    {
        $remark = Arr::get($attributes, 'remark');

        $record = $this->prepaidCardRecordRepository->find($id);
        if (!empty($record->voidRecord)) {
            throw new AlreadyVoidedException('payment already voided', $record);
        }

        // need lock row for update
        $memberPrepaidCard = $this->prepaidCardRepository->getByMemberIdWithLock($record->member_id);
        
        $newBalance = $memberPrepaidCard->balance + $record->payment;

        $newMemberPrepaidCard = $this->prepaidCardRepository->update([
            'balance' => $newBalance
        ], $memberPrepaidCard->id);

        $record = $this->prepaidCardRecordRepository->voidPaymentRecord([
            'member_id' => $record->member_id,
            'branch_id' => $record->branch_id,
            'payment' => -1 * $record->payment,
            'remark' => $remark,
            'void_id' => $id
        ]);

        return $record;
    }
}
