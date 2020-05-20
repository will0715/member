<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\UserRepository;
use App\Helpers\UserHelper;
use App\Exceptions\ResourceNotFoundException;
use Auth;
use Cache;

class UserService
{

    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
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
        $user = $this->userRepository->create($data);
        return $user;
    }

    public function updateUser($data, $id)
    {
        $user = $this->userRepository->update($data, $id);
        return $user;
    }

    public function deleteUser($id)
    {
        $user = $this->findUser($id);
        $user->delete();
        return $user;
    }
}
