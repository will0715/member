<?php

namespace App\Models;

use App\Models\BaseModel as Model;

/**
 * Class ChopRecord
 * @package App\Models
 * @version April 12, 2020, 8:24 am UTC
 *
 * @property \App\Models\Member member
 * @property \App\Models\Branch branch
 * @property string member_id
 * @property string branch_id
 * @property string type
 * @property integer chops
 * @property integer consume_chops
 */
class ChopRecord extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'chop_records';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'member_id',
        'branch_id',
        'transaction_id',
        'rule_id',
        'type',
        'chops',
        'consume_chops',
        'void_id'
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
        'transaction_id' => 'string',
        'rule_id' => 'string',
        'type' => 'string',
        'chops' => 'integer',
        'consume_chops' => 'integer',
        'void_id' => 'string'
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
        'chops' => 'required',
        'consume_chops' => 'required'
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
        return $this->hasOne(\App\Models\ChopRecord::class, 'void_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function voidedRecord()
    {
        return $this->belongsTo(\App\Models\ChopRecord::class, 'id', 'void_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function transaction()
    {
        return $this->belongsTo(\App\Models\Transaction::class, 'transaction_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function earnChopRule()
    {
        return $this->belongsTo(\App\Models\EarnChopRule::class, 'rule_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function consumeChopRule()
    {
        return $this->belongsTo(\App\Models\ConsumeChopRule::class, 'rule_id');
    }
}
