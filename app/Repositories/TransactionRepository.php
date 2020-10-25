<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

/**
 * Class TransactionRepository
 * @package App\Repositories
 * @version April 8, 2020, 1:44 pm UTC
*/

class TransactionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'branch_id',
        'order_id',
        'payment_type',
        'clerk',
        'chops',
        'consume_chops',
        'items_count',
        'amount',
        'remark',
        'status',
        'transaction_time'
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
        return Transaction::class;
    }

    public function findValid()
    {
        return $this->findWhere(['status' => 1]);
    }

    public function findByOrderId($orderId)
    {
        return $this->findByField('order_id', $orderId)->first();
    }

    public function listByMemberId($memberId, $paginate = false)
    {
        $this->where('member_id', $memberId);
        return $this->getListData($paginate);
    }

    public function createTransaction(array $attributes)
    {
        $transaction =  $this->create($attributes);

        return $transaction;
    }

    public function voidTransaction($id)
    {
        $transaction = $this->find($id);
        $transaction->void();
        return $transaction;
    }

    public function getWithMemberGender()
    {
        $data = $this->with(['member:id,gender'])->findWhere(['status' => 1]);
        return $data;
    }
}
