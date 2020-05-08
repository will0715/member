<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Member
 * @package App\Models
 * @version April 3, 2020, 4:25 am UTC
 *
 * @property string phone
 * @property string first_name
 * @property string last_name
 * @property string password
 * @property string gender
 * @property string email
 * @property string address
 * @property string remark
 */
class Member extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'members';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guard = 'members';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public $fillable = [
        'phone',
        'first_name',
        'last_name',
        'password',
        'gender',
        'email',
        'address',
        'remark',
        'rank_id',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'phone' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'password' => 'string',
        'gender' => 'string',
        'email' => 'string',
        'address' => 'string',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'phone' => 'required|unique:App\Models\Member,phone',
        'first_name' => 'required',
        'last_name' => 'required',
        'password' => 'required',
        'gender' => 'required|in:male,female,others,unknown',
        'email' => 'email',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function rank()
    {
        return $this->belongsTo(\App\Models\Rank::class);
    }

    public function chops()
    {
        return $this->hasMany(\App\Models\Chop::class);
    }

    public function chopRecords()
    {
        return $this->hasMany(\App\Models\ChopRecord::class);
    }

    public function orderRecords()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }
    
}
