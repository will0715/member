<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model as Model;
use Str;

class BaseModel extends Model
{
    use Uuid;
    const SCHEMA_PREFIX = 'db_';

    const DB_CONNECTION = 'pgsql';
    protected $connection = 'pgsql';

    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    static public function schema($customerName)
    {
        return static::SCHEMA_PREFIX.$customerName;
    }

}
