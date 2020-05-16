<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\CustomerRepository;
use App\Helpers\CustomerHelper;
use App\Exceptions\ResourceNotFoundException;
use Poyi\PGSchema\Facades\PGSchema;
use Auth;
use Cache;

class CustomerService
{

    public function __construct()
    {
        $this->customerRepository = app(CustomerRepository::class);
    }

    public function listCustomers($request)
    {
        $this->customerRepository->pushCriteria(new RequestCriteria($request));
        $this->customerRepository->pushCriteria(new LimitOffsetCriteria($request));
        $customers = $this->customerRepository->all();

        return $customers;
    }

    public function findCustomer($id)
    {
        $customer = $this->customerRepository->findWithoutFail($id);
        if (!$customer) {
            throw new ResourceNotFoundException('Customer not exist');
        }
        return $customer;
    }

    public function findCustomerByAccount($account)
    {
        $customer = $this->customerRepository->getByAccount($account);
        if (!$customer) {
            throw new ResourceNotFoundException('Customer not exist');
        }
        return $customer;
    }

    public function newCustomer($data)
    {
        $customer = $this->customerRepository->create($data);
        return $customer;
    }

    public function updateCustomer($data, $id)
    {
        $customer = $this->customerRepository->update($data, $id);
        return $customer;
    }

    public function deleteCustomer($id)
    {
        $customer = $this->findCustomer($id);
        $customer->delete();
        return $customer;
    }
}
