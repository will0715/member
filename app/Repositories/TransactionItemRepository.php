<?php

namespace App\Repositories;

use App\Models\TransactionItem;
use App\Repositories\BaseRepository;

/**
 * Class TransactionItemRepository
 * @package App\Repositories
 * @version April 8, 2020, 1:45 pm UTC
*/

class TransactionItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'transaction_id',
        'item_no',
        'item_name',
        'item_condiments',
        'quantity',
        'price',
        'subtotal',
        'remark'
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
        return TransactionItem::class;
    }
}
