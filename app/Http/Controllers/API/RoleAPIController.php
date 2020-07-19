<?php

namespace App\Http\Controllers\API;

use App\Constants\RoleConstant;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateRoleAPIRequest;
use App\Http\Requests\API\UpdateRoleAPIRequest;
use App\Http\Requests\API\UpdateRolePermissionsAPIRequest;
use App\Http\Resources\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Response;
use Log;

/**
 * Class RoleAPIController
 * @package App\Http\Controllers
 */

class RoleAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->roleService = app(RoleService::class);
    }

    /**
     * Display a listing of the Role.
     * GET|HEAD /roles
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $roles = $this->roleService->listRoles($request);
        $roles->load(RoleConstant::ROLE_RELATIONS);

        return $this->sendResponse(Role::collection($roles), 'Roles retrieved successfully');
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
        $input = $request->all();

        $role = $this->roleService->newRole($input);
        return $this->sendResponse(new Role($role), 'Role saved successfully');
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
        $role = $this->roleService->findRole($id);
        $role->load(RoleConstant::ROLE_RELATIONS);

        return $this->sendResponse(new Role($role), 'Role retrieved successfully');
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

        $role = $this->roleService->updateRole($input, $id);
        return $this->sendResponse(new Role($role), 'Role updated successfully');
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
        $role = $this->roleService->deleteRole($id);
        return $this->sendSuccess('Role deleted successfully');
    }

    public function setPermission($id, UpdateRolePermissionsAPIRequest $request)
    {
        $input = $request->all();

        $role = $this->roleService->setPermission($input, $id);
        return $this->sendSuccess('Role set permission successfully');
    }
}
