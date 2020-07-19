<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Customer
 * @package App\Models
 * @version June 20, 2017, 4:21 pm UTC
 */
class Customer extends Model
{
    use SoftDeletes;
    
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'public.customers';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    protected $fillable = [
        'name', 
        'db_schema', 
        'status', 
        'expired_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'db_schema' => 'string',
        'status' => 'integer',
        'expired_at' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'db_schema' => 'required',
        'status' => 'required|numeric',
    ];

    public function getSchema(){
        return $this->db_schema;
    }
}
