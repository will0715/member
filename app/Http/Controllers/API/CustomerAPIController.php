<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateCustomerAPIRequest;
use App\Http\Requests\API\UpdateBranchAPIRequest;
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
     * Store a newly created Role in storage.
     * POST /roles
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

    public function setAdminRolePermission(UpdateAdminRolePermissionRequest $request)
    {
        $input = $request->all();

        $customer = $this->customerService->setAdminRolePermission($input);
        return $this->sendResponse($customer, 'Customers create successfully');
    }
}
