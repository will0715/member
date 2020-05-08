<?php

namespace App\Models;

use App\Models\BaseModel as Model;

/**
 * Class Chop
 * @package App\Models
 * @version April 7, 2020, 2:54 pm UTC
 *
 * @property \App\Models\Member member
 * @property \App\Models\Branch branch
 * @property string member_id
 * @property string branch_id
 * @property integer chops
 * @property integer consume_chops
 * @property integer status
 * @property string|\Carbon\Carbon expired_at
 */
class Chop extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'chops';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'member_id',
        'branch_id',
        'chops',
        'consume_chops',
        'status',
        'expired_at'
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
        'chops' => 'integer',
        'consume_chops' => 'integer',
        'status' => 'integer',
        'expired_at' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'member_id' => 'required',
        'branch_id' => 'required',
        'chops' => 'required',
        'consume_chops' => 'required',
        'status' => 'required'
    ];

    public function getTotalChops()
    {
        return $this->chops;
    }

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
}
