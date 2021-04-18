<?php

namespace App\Repositories;

use App\Models\Config;
use App\Repositories\BaseRepository;
use Cache;

/**
 * Class ConfigRepository
 * @package App\Repositories
 * @version April 3, 2020, 5:35 am UTC
*/

class ConfigRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'key'
    ];
    
    protected $cacheKey = 'configs';

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Config::class;
    }

    public function setValue($key, $value)
    {
        $this->model->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        setToCache($key, $value);
    }

    public function getValue($key)
    {
        $cacheData = $this->getFromCache($key);
        if ($cacheData) {
            return $cacheData;
        }
        return $this->findByField('key', $key)->first();
    }

    public function getFromCache($key)
    {
        return Cache::get($this->cacheKey . '_' . $key);
    }

    public function setToCache($key, $value)
    {
        Cache::put($this->cacheKey . '_' . $key, $value);
    }
}
