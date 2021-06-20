<?php 

namespace App\ServiceManagers;

use App\Exceptions\ResourceNotFoundException;
use App\Services\MemberService;
use App\Services\BranchService;
use App\Services\PrepaidCardService;
use App\Events\PrepaidCardTopup;
use App\Helpers\CustomerHelper;
use Arr;

class MemberPrepaidCardServiceManager {

    private $memberService;
    private $branchService;
    private $prepaidCardService;

    public function __construct() 
    {
        $this->memberService = app(MemberService::class);
        $this->branchService = app(branchService::class);
        $this->prepaidCardService = app(PrepaidCardService::class);
    }

    public function getMemberBalance($attributes)
    {
        $phone = $attributes['phone'];

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        return $member->prepaidCard ?: [
            'member_id' => $member->id,
            'balance' => 0,
        ];
    }

    public function getMemberPrepaidCardRecords($attributes)
    {
        $phone = $attributes['phone'];

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        $records = $this->prepaidCardService->findPrepaidcardRecordsByMember($member->id);

        return $records;
    }

    public function topup($attributes)
    {
        $customer = CustomerHelper::getCustomer();
        $phone = $attributes['phone'];
        $branchId = $attributes['branch_id'];
        $topup = $attributes['topup'];
        $remark = $attributes['remark'];

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        // search branch
        $branch = $this->branchService->findBranchByCode($branchId);

        // topup
        $prepaidCard = $this->prepaidCardService->topup([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'topup' => $topup,
            'remark' => $remark
        ]);

        event(new PrepaidCardTopup($customer, $member, $topup));

        return $prepaidCard;
    }

    public function payment($attributes)
    {
        $phone = $attributes['phone'];
        $branchId = $attributes['branch_id'];
        $payment = $attributes['payment'];
        $remark = $attributes['remark'];
        $transactionNo = Arr::get($attributes, 'transaction_no');

        // search member
        $member = $this->memberService->findMemberByPhone($phone);

        // search branch
        $branch = $this->branchService->findBranchByCode($branchId);

        // payment
        $prepaidCard = $this->prepaidCardService->payment([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'payment' => $payment,
            'remark' => $remark,
            'transaction_no' => $transactionNo,
        ]);

        return $prepaidCard;
    }

    public function voidPayment($id, $attributes)
    {
        // payment
        $prepaidCard = $this->prepaidCardService->voidPayment($id, $attributes);

        return $prepaidCard;
    }
}