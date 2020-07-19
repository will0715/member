<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TransactionItemCondiment
 * @package App\Models
 * @version April 8, 2020, 1:45 pm UTC
 *
 * @property \App\Models\Transaction transaction
 * @property string transaction_id
 * @property string item_no
 * @property string item_name
 * @property string item_condiments
 * @property integer quantity
 * @property number price
 * @property number subtotal
 * @property string remark
 */
class TransactionItemCondiment extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'transaction_item_condiments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'transaction_item_id',
        'no',
        'name',
        'quantity',
        'price',
        'subtotal',
        'remark'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'transaction_item_id' => 'string',
        'no' => 'string',
        'name' => 'string',
        'quantity' => 'integer',
        'price' => 'float',
        'subtotal' => 'float',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'transaction_item_id' => 'required',
        'no' => 'required',
        'name' => 'required',
        'quantity' => 'required',
        'price' => 'required',
        'subtotal' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function transactionItem()
    {
        return $this->belongsTo(\App\Models\TransactionItem::class, 'transaction_item_id');
    }
}
