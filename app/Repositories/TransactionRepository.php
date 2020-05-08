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

    public function getByOrderId($orderId)
    {
        return $this->findByField('order_id', $orderId)->first();
    }

    public function createTransaction(array $attributes, Collection $transactionItems)
    {
        $transaction = null;
        $attributes['items_count'] = count($transactionItems);

        $transaction =  $this->create($attributes);
        // TODO: transaction data check

        $transactionItems = $transactionItems->map(function ($item, $key) {
            $orderItem = [
                'item_no' => $item['no'],
                'item_name' => $item['name'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
                'quantity' => $item['qty'],
                'item_condiments' => $item['condiments'] ?: ''
            ];
            return $orderItem;
        });

        $transaction->transactionItems()->createMany($transactionItems->toArray());

        return $transaction;
    }

    public function voidTransaction($id)
    {
        $transaction = $this->find($id);
        $transaction->void();
        return $transaction;
    }
}
