<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as BaseRole;
use App\Traits\Uuid;

/**
 * Class Role
 * @package App\Models
 * @version April 2, 2020, 4:32 pm UTC
 */
class Role extends BaseRole
{
    use Uuid;
    
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'roles';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];
}
