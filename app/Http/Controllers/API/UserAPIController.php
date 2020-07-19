<?php

namespace App\Http\Controllers\API;

use App\Constants\UserConstant;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Http\Resources\User;
use App\Services\CustomerService;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Poyi\PGSchema\Facades\PGSchema;
use Response;
use Auth;
use Log;

/**
 * Class UserAPIController
 * @package App\Http\Controllers\API
 */

class UserAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->authService = app(AuthService::class);
        $this->userService = app(UserService::class);
        $this->customerService = app(CustomerService::class);
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $users = $this->userService->listUsers($request);

        return $this->sendResponse(User::collection($users), 'Users retrieved successfully');
    }

    public function login(Request $request)
    {
    	$customer = $request->get('customer');
    	$email = $request->get('email');
    	$password = $request->get('password');
        
        try {
            $customer = $this->customerService->findCustomerByAccount($customer);
            
            PGSchema::schema($customer->getSchema(), 'pgsql');

            $token = $this->authService->login([
                'email' => $email,
                'password' => $password
            ]);

            return $this->sendResponse($token, 'Login successfully');
        } catch (\Exception $e) {
            Log::error($e);
    		return $this->sendError('Unauthenticated', 401);
        }
    }

    public function me()
    {
        $user = $this->authService->getLoginedUser();
        $user->load(UserConstant::USER_RELATIONS);

        return $this->sendResponse(new User($user), 'User retrieved successfully');
    }

    /**
     * Store a newly created User in storage.
     * POST /users
     *
     * @param CreateUserAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUserAPIRequest $request)
    {
        $input = $request->all();

        $user = $this->userService->newUser($input);

        return $this->sendResponse(new User($user), 'User saved successfully');
    }

    /**
     * Display the specified User.
     * GET|HEAD /users/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userService->findUser($id);
        $user->load(UserConstant::USER_RELATIONS);
        
        return $this->sendResponse(new User($user), 'User retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     * PUT/PATCH /users/{id}
     *
     * @param int $id
     * @param UpdateUserAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserAPIRequest $request)
    {
        $input = $request->all();

        $user = $this->userService->updateUser($input, $id);
        return $this->sendResponse(new User($user), 'User updated successfully');
    }

    /**
     * Remove the specified User from storage.
     * DELETE /users/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userService->deleteUser($id);
        return $this->sendSuccess('User deleted successfully');
    }
}
