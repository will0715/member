<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MemberSocialite
 * @package App\Models
 *
 */
class MemberSocialite extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'member_socialites';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'member_id',
        'socialite_provider',
        'socialite_user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'member_id' => 'string',
        'socialite_provider' => 'string',
        'socialite_user_id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'member_id' => 'required',
        'socialite_provider' => 'required',
        'socialite_user_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class, 'member_id');
    }
}
