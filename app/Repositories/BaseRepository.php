<?php

namespace App\Repositories;

use InfyOm\Generator\Common\BaseRepository as InfyOmBaseRepository;
use Carbon\Carbon;


abstract class BaseRepository extends InfyOmBaseRepository
{

    /**
     * Configure the Model
     *
     * @return string
     */
    abstract public function model();

    public function whereIn($column, $where)
    {
        $this->scopeQuery(function($query) use ($column, $where) {
            return $query->whereIn($column, $where);
        });

        return $this;
    }

    public function createMany($allAttributes)
    {
        $allAttributes = collect($allAttributes)->map(function ($attributes) {
            $attributes['created_at'] = Carbon::now();
            $attributes['updated_at'] = Carbon::now();
            return $attributes;
        });

        $data = $this->model->insert($allAttributes->toArray());

        return $data;
    }
}
