<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as BasePermission;
use App\Traits\Uuid;

/**
 * Class Permission
 * @package App\Models
 * @version May 18, 2017, 7:53 am UTC
 */
class Permission extends BasePermission
{
    use Uuid;
    
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'public.permissions';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];
}
