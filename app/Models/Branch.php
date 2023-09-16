<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Branch
 * @package App\Models
 * @version June 20, 2017, 4:21 pm UTC
 */
class Branch extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'branches';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'code',
        'name',
        'store_name',
        'email',
        'telphone',
        'fax',
        'note',
        'zipcode',
        'state',
        'city',
        'county',
        'address',
        'latitude',
        'longitude',
        'remark',
        'opening_times',
        'is_independent',
        'disable_consume_other_branch_chop'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'code' => 'string',
        'name' => 'string',
        'store_name' => 'string',
        'email' => 'string',
        'telphone' => 'string',
        'fax' => 'string',
        'note' => 'string',
        'zipcode' => 'string',
        'state' => 'string',
        'city' => 'string',
        'county' => 'string',
        'address' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
        'remark' => 'string',
        'opening_times' => 'string',
        'disable_consume_other_branch_chop' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => 'required|unique:branches,code',
        'name' => 'required',
        'store_name' => 'required',
    ];

    public function fullAddress()
    {
        return $this->state.$this->city.$this->county.$this->address;
    }

    public function getPOSBranchId()
    {
        return $this->code;
    }

    public function getStoreNameWithId()
    {
        return $this->store_name.' ('.$this->code.')';
    }

    public function getStoreName()
    {
        return $this->store_name;
    }

    public function isIndependent()
    {
        return $this->is_independent;
    }

    public function isDisableConsumeOtherBranchChop()
    {
        return !$this->is_independent && $this->disable_consume_other_branch_chop;
    }

    public function registerMembers()
    {
        return $this->hasMany(\App\Models\Member::class, 'register_branch_id');
    }
}
