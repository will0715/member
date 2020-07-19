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
            throw new ResourceNotFoundException('User not exist');
        }
        return $user;
    }

    public function newUser($data)
    {
        $roleId = Arr::get($data, 'role');
        $user = $this->userRepository->create($data);

        if ($roleId) {
            $this->setRole($user, $roleId);
        }

        return $user;
    }

    public function updateUser($data, $id)
    {
        $roleId = Arr::get($data, 'role');
        $user = $this->userRepository->update($data, $id);

        if ($roleId) {
            $this->setRole($user, $roleId);
        }

        return $user;
    }

    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }

    public function setRole($user, $roleId)
    {
        $role = $this->roleRepository->find($roleId);
        $user->assignRole($role->name);

        return $user;
    }
}
