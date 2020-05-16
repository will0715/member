<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\RoleRepository;
use App\Helpers\CustomerHelper;
use App\Exceptions\ResourceNotFoundException;
use Cache;

class RoleService
{

    public function __construct()
    {
        $this->roleRepository = app(RoleRepository::class);
    }

    public function listRoles($request)
    {
        $this->roleRepository->pushCriteria(new RequestCriteria($request));
        $this->roleRepository->pushCriteria(new LimitOffsetCriteria($request));
        $roles = $this->roleRepository->all();

        return $roles;
    }

    public function findRole($id)
    {
        $role = $this->roleRepository->findWithoutFail($id);
        return $role;
    }

    public function newRole($data)
    {
        $role = $this->roleRepository->create($data);
        return $role;
    }

    public function updateRole($data, $id)
    {
        $role = $this->roleRepository->update($data, $id);
        return $role;
    }

    public function deleteRole($id)
    {
        $role = $this->findRole($id);
        $role->delete();
        return $role;
    }
}
