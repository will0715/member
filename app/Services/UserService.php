<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Helpers\UserHelper;
use App\Exceptions\ResourceNotFoundException;
use Auth;
use Cache;
use Arr;

class UserService
{

    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
        $this->roleRepository = app(RoleRepository::class);
    }

    public function listUsers($request)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));
        $users = $this->userRepository->all();

        return $users;
    }

    public function findUser($id)
    {
        $user = $this->userRepository->findWithoutFail($id);
        if (!$user) {
            throw new ResourceNotFoundException('User Not Found');
        }
        return $user;
    }

    public function newUser($data)
    {
        $roleId = Arr::get($data, 'role_id');
        $user = $this->userRepository->newUser($data);

        // 預設使用與創建者一樣的權限
        if (!$roleId) {
            $roleId = Auth::user()->roles->pluck('id');
        }
        $this->setRoles($user, Arr::wrap($roleId));

        return $user;
    }

    public function updateUser($data, $id)
    {
        $roleId = Arr::get($data, 'role_id');
        $user = $this->userRepository->updateUser($data, $id);

        if ($roleId) {
            $this->setRoles($user, Arr::wrap($roleId));
        }

        return $user;
    }

    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }

    public function setRoles($user, $roleIds)
    {
        $roles = $this->roleRepository->findWhereIn('id', $roleIds);
        $user->syncRoles([$roles]);

        return $user;
    }
}
