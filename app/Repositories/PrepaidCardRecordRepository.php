<?php

namespace App\Repositories;

use App\Models\PrepaidCardRecord;
use App\Repositories\BaseRepository;
use App\Constants\PrepaidCardConstant;
use Arr;

/**
 * Class PrepaidCardRecordRepository
 * @package App\Repositories
 * @version April 12, 2020, 8:24 am UTC
*/

class PrepaidCardRecordRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member.first_name' => 'like',
        'member.last_name' => 'like',
        'member.phone' => 'like',
        'branch.name',
        'transaction_no',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PrepaidCardRecord::class;
    }

    public function newTopUpRecord($data)
    {
        return $this->create([
            'member_id' => $data['member_id'],
            'branch_id' => $data['branch_id'],
            'type' => PrepaidCardConstant::PREPAIDCARD_TYPE_TOPUP,
            'topup' => $data['topup'],
            'payment' => 0,
            'void_id' => null,
            'transaction_no' => Arr::get($data, 'transaction_no'),
            'remark' => $data['remark'],
        ]);
    }

    public function newPaymentRecord($data)
    {
        return $this->create([
            'member_id' => $data['member_id'],
            'branch_id' => $data['branch_id'],
            'type' => PrepaidCardConstant::PREPAIDCARD_TYPE_PAYMENT,
            'topup' => 0,
            'payment' => $data['payment'],
            'void_id' => null,
            'transaction_no' => Arr::get($data, 'transaction_no'),
            'remark' => $data['remark'],
        ]);
    }

    public function voidPaymentRecord($data)
    {
        return $this->create([
            'member_id' => $data['member_id'],
            'branch_id' => $data['branch_id'],
            'type' => PrepaidCardConstant::PREPAIDCARD_TYPE_VOID_PAYMENT,
            'topup' => 0,
            'payment' => $data['payment'],
            'void_id' => $data['void_id'],
            'transaction_no' => Arr::get($data, 'transaction_no'),
            'remark' => $data['remark'],
        ]);
    }

    public function findTopup()
    {
        return $this->findWhereIn('type', [PrepaidCardConstant::PREPAIDCARD_TYPE_TOPUP]);
    }

    public function findPayment()
    {
        return $this->findWhereIn('type', [PrepaidCardConstant::PREPAIDCARD_TYPE_PAYMENT]);
    }

    public function findVoidPayment()
    {
        return $this->findWhereIn('type', [PrepaidCardConstant::PREPAIDCARD_TYPE_VOID_PAYMENT]);
    }

    public function findPaymentAndVoidPayment()
    {
        return $this->findWhereIn('type', [
            PrepaidCardConstant::PREPAIDCARD_TYPE_PAYMENT,
            PrepaidCardConstant::PREPAIDCARD_TYPE_VOID_PAYMENT
        ]);
    }
}
