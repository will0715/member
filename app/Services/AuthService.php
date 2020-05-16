<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\UserRepository;
use App\Helpers\UserHelper;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Auth;
use Cache;

class AuthService
{

    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
    }

    public function login($attributes)
    {
        $email = $attributes['email'];
        $password = $attributes['password'];

    	if (Auth::guard('user')->attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::guard('user')->user();
            $userToken = $user->createToken('user ' . $user->id, ['*']);
            $token = [
                'token' => $userToken->accessToken,
                'expiredAt' => $userToken->token->expires_at
            ];
            return $token;
    	} else {
            throw new AuthenticationException();
    	}
    }

    public function getLoginedUser()
    {
        $user = Auth::guard('api')->user();
        $user = $this->userRepository->find($user->id);

        return $user;
    }
}
