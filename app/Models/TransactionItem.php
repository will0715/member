<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TransactionItem
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
class TransactionItem extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'transaction_items';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'transaction_id' => 'string',
        'item_no' => 'string',
        'item_name' => 'string',
        'item_condiments' => 'string',
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
        'transaction_id' => 'required',
        'item_no' => 'required',
        'item_name' => 'required',
        'item_condiments' => 'required',
        'quantity' => 'required',
        'price' => 'required',
        'subtotal' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function transaction()
    {
        return $this->belongsTo(\App\Models\Transaction::class, 'transaction_id');
    }
}
