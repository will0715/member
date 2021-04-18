<?php

namespace App\Models;

use App\Models\BaseModel as Model;

/**
 * Class PrepaidCardRecord
 */
class PrepaidCardRecord extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'prepaid_card_records';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'member_id',
        'branch_id',
        'type',
        'topup',
        'payment',
        'void_id',
        'remark',
        'transaction_no'
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
        'type' => 'string',
        'topup' => 'double',
        'payment' => 'double',
        'void_id' => 'string',
        'remark' => 'string',
        'transaction_no' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'member_id' => 'required',
        'branch_id' => 'required',
        'type' => 'required',
        'topup' => 'required',
        'payment' => 'required'
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
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     **/
    public function voidRecord()
    {
        return $this->hasOne(\App\Models\PrepaidCardRecord::class, 'void_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function voidedRecord()
    {
        return $this->belongsTo(\App\Models\PrepaidCardRecord::class, 'id', 'void_id');
    }
}
