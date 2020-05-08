<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Str;

class BaseModel extends Model
{
    const SCHEMA_PREFIX = 'db_';

    const DB_CONNECTION = 'pgsql';
    protected $connection = 'pgsql';

    public $incrementing = false;
    protected $keyType = 'string';

    static public function schema($customerName)
    {
        return static::SCHEMA_PREFIX.$customerName;
    }

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

}
