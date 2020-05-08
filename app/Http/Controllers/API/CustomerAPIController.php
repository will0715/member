<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use Auth;
use Poyi\PGSchema\Facades\PGSchema;

/**
 * Class CustomerAPIController
 * @package App\Http\Controllers\API
 */

class CustomerAPIController extends AppBaseController
{
    /** @var  customerRepository */
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepo)
    {
        $this->customerRepository = $customerRepo;
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
        $this->customerRepository->pushCriteria(new RequestCriteria($request));
        $this->customerRepository->pushCriteria(new LimitOffsetCriteria($request));
        $customers = $this->customerRepository->all();

        return $this->sendResponse($customers->toArray(), __('retrieved successfully'));
    }

    public function login(Request $request)
    {
    	$customer = $request->get('customer');
    	$account = $request->get('account');
    	$password = $request->get('password');
        $customer = app(CustomerRepository::class)->getByAccount($customer);
        PGSchema::schema($customer->getSchema(), 'pgsql');
    	if (Auth::guard('user')->attempt(['email' => $account, 'password' => $password])) {
            $user = Auth::guard('user')->user();
            $token = $user->createToken('user ' . $user->id, ['*']);
            $success['token'] = $token->accessToken;
    		$success['expiredAt'] = $token->token->expires_at;
            return $this->sendResponse($success, 'Login successfully');
    	} else {
    		return $this->sendError('Unauthenticated', 401);
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

        return $this->sendResponse($role->toArray(), 'Role saved successfully');
    }

    /**
     * Display the specified Role.
     * GET|HEAD /roles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Role $role */
        $role = $this->roleRepository->find($id);

        if (empty($role)) {
            return $this->sendError('Role not found');
        }

        return $this->sendResponse($role->toArray(), 'Role retrieved successfully');
    }

    /**
     * Update the specified Role in storage.
     * PUT/PATCH /roles/{id}
     *
     * @param int $id
     * @param UpdateRoleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoleAPIRequest $request)
    {
        $input = $request->all();

        /** @var Role $role */
        $role = $this->roleRepository->find($id);

        if (empty($role)) {
            return $this->sendError('Role not found');
        }

        $role = $this->roleRepository->update($input, $id);

        return $this->sendResponse($role->toArray(), 'Role updated successfully');
    }

    /**
     * Remove the specified Role from storage.
     * DELETE /roles/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Role $role */
        $role = $this->roleRepository->find($id);

        if (empty($role)) {
            return $this->sendError('Role not found');
        }

        $role->delete();

        return $this->sendSuccess('Role deleted successfully');
    }
}
