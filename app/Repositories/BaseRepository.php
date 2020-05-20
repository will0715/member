<?php

namespace App\Repositories;

use InfyOm\Generator\Common\BaseRepository as InfyOmBaseRepository;


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
}
