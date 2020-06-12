<?php

namespace App\Repositories;

use App\Criterias\ValidCustomerCriteria;
use App\Repositories\BaseRepository;
use App\Models\Customer;

class CustomerRepository extends BaseRepository
{
    public function boot(){
        $this->pushCriteria(ValidCustomerCriteria::class);
    }
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'display_name',
        'belong_tag',
        'description'
    ];

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
        return Customer::class;
    }

    public function getByAccount($account)
    {
        return $this->findByField('name', $account)->first();
    }

    public function getSchemaNameByAccount($account)
    {
        return optional($this->findByField('name', $account)->first())->schema();
    }
}
