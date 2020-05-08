<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction
 * @package App\Models
 * @version April 8, 2020, 1:44 pm UTC
 *
 * @property \App\Models\Branch branch
 * @property \Illuminate\Database\Eloquent\Collection transactionItems
 * @property string branch_id
 * @property string order_id
 * @property string payment_type
 * @property string clerk
 * @property integer chops
 * @property integer consume_chops
 * @property integer items_count
 * @property number amount
 * @property string remark
 * @property integer status
 * @property string|\Carbon\Carbon transaction_time
 */
class Transaction extends Model
{
    const STATUS_VALID = 1;
    const STATUS_VOIDED = 0;
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'transactions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'branch_id',
        'member_id',
        'chop_record_id',
        'order_id',
        'payment_type',
        'clerk',
        'earn_chops',
        'items_count',
        'amount',
        'remark',
        'status',
        'transaction_time'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'member_id' => 'string',
        'branch_id' => 'string',
        'chop_record_id' => 'string',
        'order_id' => 'string',
        'payment_type' => 'string',
        'clerk' => 'string',
        'earn_chops' => 'integer',
        'items_count' => 'integer',
        'amount' => 'float',
        'remark' => 'string',
        'status' => 'integer',
        'transaction_time' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'branch_id' => 'required',
        'order_id' => 'required',
        'payment_type' => 'required',
        'clerk' => 'required',
        'items_count' => 'required',
        'amount' => 'required',
        'status' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class, 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function transactionItems()
    {
        return $this->hasMany(\App\Models\TransactionItem::class, 'transaction_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function chopRecords()
    {
        return $this->hasMany(\App\Models\ChopRecord::class, 'transaction_id');
    }

    public function isValid()
    {
        return $this->status == self::STATUS_VALID;
    }

    public function void()
    {
        $this->status = self::STATUS_VOIDED;
        $this->save();
        return $this;
    }
}
