<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class PrepaidCard extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'prepaid_cards';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'member_id',
        'balance',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'member_id' => 'string',
        'balance' => 'double',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'member_id' => 'required',
        'balance' => 'required',
    ];

    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class, 'member_id');
    }
}
