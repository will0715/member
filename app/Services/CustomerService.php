<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\ValidCustomerCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\CustomerRepository;
use App\Repositories\BranchRepository;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Repositories\RankRepository;
use App\Repositories\ChopExpiredSettingRepository;
use App\Helpers\CustomerHelper;
use App\Events\NewCustomer;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Customer;
use Poyi\PGSchema\Facades\PGSchema;
use Auth;
use Artisan;
use Cache;
use Hash;
use DB;

class CustomerService
{

    public function __construct()
    {
        $this->customerRepository = app(CustomerRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->userRepository = app(UserRepository::class);
        $this->roleRepository = app(RoleRepository::class);
        $this->rankRepository = app(RankRepository::class);
        $this->chopExpiredSettingRepository = app(ChopExpiredSettingRepository::class);
    }

    public function includeExpiredCustomer()
    {
        $this->customerRepository->popCriteria(ValidCustomerCriteria::class);
    }

    public function excludeExpiredCustomer()
    {
        $this->customerRepository->pushCriteria(ValidCustomerCriteria::class);
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
        $customer = $this->customerRepository->create([
            'name' => $data['name'],
            'db_schema' => 'db_' . $data['name'],
            'status' => 1,
            'expired_at' => $data['expired_at'],
        ]);

        event(new NewCustomer($customer, $data));

        return $customer;
    }

    public function updateCustomer($data, $id)
    {
        $customer = $this->customerRepository->update($data, $id);
        return $customer;
    }

    public function deleteCustomer($id)
    {
        return $this->customerRepository->delete($id);
    }

    public function initCustomer($data)
    {
        $name = $data['name'];
        $account = $data['account'];
        $password = Hash::make($data['password']);
        $permissions = $data['permissions'];
        $schema = 'db_'.$name;

        $createSchema = DB::connection()->statement('create schema IF NOT EXISTS ' . $schema);

        PGSchema::schema($schema, 'pgsql');
        $migrate = Artisan::call('pgschema:migrate', ["--schema" => $schema , "--force" => "true"]);
    
        //Add HQ branch
        $branch = $this->branchRepository->create([
            'code' => 'HQ',
            'name' => 'HQ',
            'store_name' => 'HQ'
        ]);

        //Add user
        $user = $this->userRepository->create([
            'name' => $name,
            'email' => $account,
            'password' => Hash::make($password)
        ]);
        $user = $this->userRepository->findByField('name', $name)->first();

        //Add admin role
        $admin = $this->roleRepository->create([
            'name' => 'admin',
            'guard_name' => 'api'
        ]);
        $admin->syncPermissions($permissions);

        $user->assignRole('admin');

        //Add basic rank
        $rank = $this->rankRepository->create([
            'rank' => 1,
            'name' => '一般會員'
        ]);

        //Add basic chop expired setting
        $chopExpiredSetting = $this->chopExpiredSettingRepository->create([
            'expired_date' => 365,
        ]);
    }
}
