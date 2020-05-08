<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Rank
 * @package App\Models
 * @version April 3, 2020, 5:35 am UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection members
 * @property integer rank
 * @property string name
 */
class Rank extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'ranks';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'rank',
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'rank' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'rank' => 'required',
        'name' => 'required|unique:App\Models\Rank,name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function members()
    {
        return $this->hasMany(\App\Models\Member::class, 'rank_id');
    }
}
