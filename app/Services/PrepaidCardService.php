<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Criterias\RequestDateRangeCriteria;
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

    public function listRecords($request)
    {
        $this->prepaidCardRecordRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new RequestCriteria($request));
        $this->prepaidCardRecordRepository->pushCriteria(new LimitOffsetCriteria($request));
        $records = $this->prepaidCardRecordRepository->all();

        return $records;
    }

    public function findPrepaidcardRecordsByMember($memberId)
    {
        return $this->prepaidCardRecordRepository->orderBy('created_at', 'desc')->findWhere(['member_id' => $memberId]);
    }

    public function findPrepaidcardRecordsByTransactionNo($transactionNo)
    {
        return $this->prepaidCardRecordRepository->findWhere(['transaction_no' => $transactionNo]);
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
        $transactionNo = Arr::get($attributes, 'transaction_no');

        // need lock row for update
        $memberPrepaidCard = $this->prepaidCardRepository->getByMemberIdWithLock($memberId);
        $newBalance = $memberPrepaidCard ? $memberPrepaidCard->balance - $payment : -1 * $payment;

        if ($newBalance < 0) {
            throw new PrepaidCardsNotEnoughException();
        }

        $newMemberPrepaidCard = $this->prepaidCardRepository->update([
            'balance' => $newBalance
        ], $memberPrepaidCard->id);

        $record = $this->prepaidCardRecordRepository->newPaymentRecord([
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'payment' => $payment,
            'remark' => $remark,
            'transaction_no' => $transactionNo,
        ]);

        return $record;
    }

    public function voidPayment($id, $attributes = [])
    {
        $remark = Arr::get($attributes, 'remark');

        $record = $this->prepaidCardRecordRepository->find($id);
        if (!empty($record->voidRecord)) {
            throw new AlreadyVoidedException();
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
