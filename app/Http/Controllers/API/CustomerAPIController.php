<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateBranchAPIRequest;
use App\Http\Requests\API\UpdateBranchAPIRequest;
use App\Http\Resources\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Response;
use Log;

/**
 * Class CustomerAPIController
 * @package App\Http\Controllers\API
 */

class CustomerAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->customerService = app(CustomerService::class);
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
        try {
            $customers = $this->customerService->listCustomers($request);
            return $this->sendResponse(Customer::collection($customers), 'Customers retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Store a newly created Role in storage.
     * POST /roles
     *
     * @param CreateRoleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleAPIRequest $request)
    {
    	
    	$name = $request->get('name');
    	$account = $request->get('account');
        $password = $request->get('password');
        $schema = $request->get('schema');

        $this->customerRepository->create([
            'name' => $name,
            'db_schema' => $schema,
            'account' => $account,
            'password' => Hash::make($password),
        ]);

        return $this->sendResponse(new Customer($role), 'Role saved successfully');
    }
}
