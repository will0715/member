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

    public function where($column, $where)
    {
        $this->scopeQuery(function($query) use ($column, $where) {
            return $query->where($column, $where);
        });

        return $this;
    }

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

    public function forceDelete($id)
    {
        $data = $this->model->withTrashed()->findOrFail($id);
        $data->forceDelete();
        return $data;
    }

    public function findBySearchableField(array $searchable)
    {
        $this->scopeSeachableField($searchable);
        
        $data = $this->model->get();
        $this->resetModel();
        
        return $data;
    }

    public function findFirstBySearchableField(array $searchable)
    {
        $this->scopeSeachableField($searchable);

        $data = $this->model->first();
        $this->resetModel();
        
        return $data;
    }

    protected function scopeSeachableField(array $searchable)
    {
        foreach ($searchable as $searchableField => $searchableValue) {
            if (array_key_exists($searchableField, $this->getFieldsSearchable()) || 
                in_array($searchableField, $this->getFieldsSearchable())) {
                    $this->model = $this->model->where($searchableField, $searchableValue);
            }
        }
    }

    protected function getListData($paginate = false)
    {
        if ($paginate) {
            return $this->paginate();
        }
        return $this->get();
    }
}
