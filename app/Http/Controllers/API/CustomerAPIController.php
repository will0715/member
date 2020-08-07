<?php

namespace App\Http\Controllers\API;

use App\Constants\RoleConstant;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateCustomerAPIRequest;
use App\Http\Requests\API\UpdateCustomerAPIRequest;
use App\Http\Requests\API\UpdateAdminRolePermissionRequest;
use App\Http\Resources\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Response;
use Log;
use DB;

/**
 * Class CustomerAPIController
 * @package App\Http\Controllers\API
 */

class CustomerAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->customerService = app(CustomerService::class);
        $this->customerService->includeExpiredCustomer();
    }

    /**
     * Display a listing of the branch.
     * GET|HEAD /branches
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $customers = $this->customerService->listCustomers($request);
        return $this->sendResponse(Customer::collection($customers), 'Customers retrieved successfully');
        
    }

    /**
     * Store a newly created Customer in storage.
     * POST /customers
     *
     * @param CreateCustomerAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCustomerAPIRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $customer = $this->customerService->newCustomer($input);
            DB::commit();
            
            return $this->sendResponse(new Customer($customer), 'Customers create successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Display the specified Customer.
     * GET|HEAD /customers/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $customer = $this->customerService->findCustomer($id);

        return $this->sendResponse(new Customer($customer), 'Customer retrieved successfully');
    }

    /**
     * Update the specified Customer in storage.
     * PUT/PATCH /customers/{id}
     *
     * @param int $id
     * @param UpdateCustomerAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCustomerAPIRequest $request)
    {
        $input = $request->all();

        $customer = $this->customerService->updateCustomer($input, $id);
        return $this->sendResponse(new Customer($customer), 'Customer updated successfully');
    }

    /**
     * Remove the specified Customer from storage.
     * DELETE /customers/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $customer = $this->customerService->deleteCustomer($id);
        return $this->sendSuccess('Customer deleted successfully');
    }

    public function getAdminRolePermission($id)
    {
        $role = $this->customerService->getAdminRolePermission($id);
        $role->load(RoleConstant::ROLE_RELATIONS);

        return $this->sendResponse($role, 'Rule retrieved successfully');
    }

    public function setAdminRolePermission($id, UpdateAdminRolePermissionRequest $request)
    {
        $input = $request->all();

        $customer = $this->customerService->setAdminRolePermission($input, $id);
        return $this->sendResponse($customer, 'Rule update successfully');
    }
}
